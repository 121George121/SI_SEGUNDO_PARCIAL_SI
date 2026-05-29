<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\Evaluacion;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Postulante;
use App\Helpers\LoggerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocenteController extends Controller
{
    // Fetch currently logged-in Docente's profile
    protected function getDocente()
    {
        return Docente::where('id_docente', Auth::user()->id_persona)->firstOrFail();
    }

    // Docente Dashboard
    public function dashboard()
    {
        $docente = $this->getDocente();
        
        // Find assigned groups
        $grupos = Grupo::with(['aula', 'modalidad', 'turno', 'gestion', 'materias'])
                       ->where('id_docente', $docente->id_docente)
                       ->get();

        return view('dashboard.docente', compact('grupos'));
    }

    // View students, evaluations, and grading interface for a specific group
    public function viewGrupo($id)
    {
        $docente = $this->getDocente();
        
        // Verify group belongs to docente
        $grupo = Grupo::with(['aula', 'turno', 'gestion', 'materias'])
                      ->where('id_grupo', $id)
                      ->where('id_docente', $docente->id_docente)
                      ->firstOrFail();

        // Get group students
        $postulantes = $grupo->postulantes()->with('persona')->get();

        // Get group evaluations
        $evaluaciones = Evaluacion::with('materia')
                                  ->where('id_grupo', $id)
                                  ->orderBy('numero_evaluacion', 'asc')
                                  ->get();

        $materias = $grupo->materias;

        return view('docente.grupo', compact('grupo', 'postulantes', 'evaluaciones', 'materias'));
    }

    // --- CRUD EVALUACIONES ---
    public function storeEvaluacion(Request $request, $grupoId)
    {
        $request->validate([
            'numero_evaluacion' => 'required|integer|min:1',
            'porcentaje' => 'required|numeric|min:1|max:100',
            'fecha' => 'required|date',
            'id_materia' => 'required|integer|exists:materia,id_materia',
        ]);

        $docente = $this->getDocente();
        $grupo = Grupo::where('id_grupo', $grupoId)->where('id_docente', $docente->id_docente)->firstOrFail();

        // Validate total percentage doesn't exceed 100%
        $currentSum = Evaluacion::where('id_grupo', $grupoId)->sum('porcentaje');
        if (($currentSum + $request->porcentaje) > 100.0) {
            return back()->withErrors(['porcentaje' => 'La suma total de los porcentajes de evaluación no puede exceder el 100%.']);
        }

        $evaluacion = Evaluacion::create([
            'numero_evaluacion' => $request->numero_evaluacion,
            'porcentaje' => $request->porcentaje,
            'fecha' => $request->fecha,
            'estado' => 'Activo',
            'id_grupo' => $grupoId,
            'id_materia' => $request->id_materia,
        ]);

        LoggerHelper::log('CREATE', 'Evaluación creada', "Grupo ID: {$grupoId}, Eval: {$request->numero_evaluacion}");
        return back()->with('success', 'Evaluación registrada con éxito.');
    }

    public function destroyEvaluacion($id)
    {
        $docente = $this->getDocente();
        $evaluacion = Evaluacion::findOrFail($id);
        
        // Verify group ownership
        $grupo = Grupo::where('id_grupo', $evaluacion->id_grupo)->where('id_docente', $docente->id_docente)->firstOrFail();

        $evaluacion->delete();
        LoggerHelper::log('DELETE', 'Evaluación eliminada', "Eval ID: {$id}");
        return back()->with('success', 'Evaluación eliminada con éxito.');
    }

    // --- CARGA DE NOTAS (GRADING) ---
    public function viewNotas($grupoId, $evaluacionId)
    {
        $docente = $this->getDocente();
        $grupo = Grupo::where('id_grupo', $grupoId)->where('id_docente', $docente->id_docente)->firstOrFail();
        $evaluacion = Evaluacion::where('id_evaluacion', $evaluacionId)->where('id_grupo', $grupoId)->firstOrFail();

        $postulantes = $grupo->postulantes()->with('persona')->get();

        // Get already registered grades for this evaluation
        $notas = Nota::where('id_evaluacion', $evaluacionId)->get()->keyBy('id_postulante');

        return view('docente.notas', compact('grupo', 'evaluacion', 'postulantes', 'notas'));
    }

    public function storeNotas(Request $request, $grupoId, $evaluacionId)
    {
        $docente = $this->getDocente();
        $grupo = Grupo::where('id_grupo', $grupoId)->where('id_docente', $docente->id_docente)->firstOrFail();
        $evaluacion = Evaluacion::where('id_evaluacion', $evaluacionId)->where('id_grupo', $grupoId)->firstOrFail();

        $grades = $request->input('notas', []);

        DB::beginTransaction();
        try {
            foreach ($grades as $postulanteId => $gradeValue) {
                if ($gradeValue === null || $gradeValue === '') continue;

                // Validate grade between 0 and 100
                $gradeValue = floatval($gradeValue);
                if ($gradeValue < 0 || $gradeValue > 100) {
                    throw new \Exception("La nota debe estar entre 0 y 100.");
                }

                // Academic status (Bolivian system: >= 51 is approved)
                $status = ($gradeValue >= 51.0) ? 'Aprobado' : 'Reprobado';

                // Insert or Update grade
                Nota::updateOrCreate(
                    [
                        'id_evaluacion' => $evaluacionId,
                        'id_grupo' => $grupoId,
                        'id_postulante' => $postulanteId,
                    ],
                    [
                        'nota' => $gradeValue,
                        'estado_academico' => $status,
                        'fecha' => now()->toDateString(),
                    ]
                );
            }

            DB::commit();
            LoggerHelper::log('UPDATE', 'Carga de Notas realizada', "Grupo: {$grupo->sigla_grupo}, Evaluación: {$evaluacion->numero_evaluacion}");
            return redirect()->route('docente.grupo.view', $grupoId)->with('success', 'Notas cargadas y promedios actualizados automáticamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al guardar notas: ' . $e->getMessage()]);
        }
    }

    // --- CARGA DE ASISTENCIAS (ATTENDANCE) ---
    public function viewAsistencia($grupoId)
    {
        $docente = $this->getDocente();
        $grupo = Grupo::with('materias')->where('id_grupo', $grupoId)->where('id_docente', $docente->id_docente)->firstOrFail();

        $postulantes = $grupo->postulantes()->with('persona')->get();
        $materias = $grupo->materias;

        return view('docente.asistencia', compact('grupo', 'postulantes', 'materias'));
    }

    public function storeAsistencia(Request $request, $grupoId)
    {
        $request->validate([
            'id_materia' => 'required|integer|exists:materia,id_materia',
            'fecha' => 'required|date',
            'asistencias' => 'required|array',
        ]);

        $docente = $this->getDocente();
        $grupo = Grupo::where('id_grupo', $grupoId)->where('id_docente', $docente->id_docente)->firstOrFail();

        $asistencias = $request->input('asistencias', []);
        $observaciones = $request->input('observaciones', []);

        DB::beginTransaction();
        try {
            foreach ($asistencias as $postulanteId => $estado) {
                // Validate state is Presente, Ausente, Tarde
                if (!in_array($estado, ['Presente', 'Ausente', 'Tarde'])) {
                    continue;
                }

                Asistencia::create([
                    'fecha' => $request->fecha,
                    'hora' => now()->toTimeString(),
                    'estado' => $estado,
                    'observacion' => $observaciones[$postulanteId] ?? null,
                    'id_materia' => $request->id_materia,
                    'id_grupo' => $grupoId,
                    'id_postulante' => $postulanteId,
                ]);
            }

            DB::commit();
            LoggerHelper::log('CREATE', 'Registro de Asistencia', "Grupo: {$grupo->sigla_grupo}, Fecha: {$request->fecha}");
            return redirect()->route('docente.grupo.view', $grupoId)->with('success', 'Asistencias registradas correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al guardar asistencias: ' . $e->getMessage()]);
        }
    }
}
