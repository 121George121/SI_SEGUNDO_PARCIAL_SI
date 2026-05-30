<?php

namespace App\Http\Controllers\Gestion_Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\Carrera;
use App\Models\Modalidad;
use App\Models\Turno;
use App\Models\Aula;
use App\Models\Docente;
use App\Models\Gestion;
use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class GruposController extends Controller
{
    
    public function index(Request $request)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if (!in_array($rol, ['Administrador', 'SuperAdministrador', 'Admin'])) {
            abort(403, 'No autorizado');
        }

     
        $this->ensureDatabaseSeeded();

        
        $query = Grupo::with(['carrera', 'modalidad', 'turno', 'aula', 'docente', 'gestion']);

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('sigla_grupo', 'ILIKE', "%{$search}%");
        }

        // Filtro por Carrera
        if ($request->filled('carrera_id')) {
            $query->where('id_carrera', $request->carrera_id);
        }

        // Filtro por Modalidad
        if ($request->filled('modalidad_id')) {
            $query->where('id_modalidad', $request->modalidad_id);
        }

        $grupos = $query->orderBy('id_grupo', 'asc')->get();

        $carreras = Carrera::all();
        $modalidades = Modalidad::all();
        $turnos = Turno::all();
        $aulas = Aula::all();
        $gestiones = Gestion::all();
        $docentes = Docente::with('persona')->get();

        return view('Gestion_Academica.grupos', compact('grupos', 'carreras', 'modalidades', 'turnos', 'aulas', 'gestiones', 'docentes'));
    }


    public function store(Request $request)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if (!in_array($rol, ['Administrador', 'SuperAdministrador', 'Admin'])) {
            abort(403, 'No autorizado');
        }

        $this->ensureDatabaseSeeded();

        $request->validate([
            'sigla_grupo' => 'required|string|unique:grupo,sigla_grupo',
            'id_carrera' => 'required|integer|exists:carrera,id_carrera',
            'id_modalidad' => 'required|integer|exists:modalidad,id_modalidad',
            'id_turno' => 'required|integer|exists:turno,id_turno',
            'capacidad_max' => 'required|integer|min:1',
            'estado' => 'required|string|in:Activo,Inactivo',
            'descripcion' => 'nullable|string',
        ]);

        
        $aula = Aula::first();
        $docente = Docente::first();
        $gestion = Gestion::first();

        if (!$aula || !$docente || !$gestion) {
            return redirect()->back()->withErrors(['error' => 'No existen suficientes datos de base (Aulas, Docentes o Gestiones) para registrar un grupo.']);
        }

        $grupo = Grupo::create([
            'sigla_grupo' => $request->sigla_grupo,
            'id_carrera' => $request->id_carrera,
            'id_modalidad' => $request->id_modalidad,
            'id_turno' => $request->id_turno,
            'capacidad_max' => $request->capacidad_max,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
            'cant_estudiantes' => 0, 
            'id_aula' => $aula->id_aula,
            'id_docente' => $docente->id_docente,
            'id_gestion' => $gestion->id_gestion,
        ]);

        $this->registrarBitacora('Registrar Grupo', 'Grupo registrado: ' . $grupo->sigla_grupo);

        return redirect()->back()->with('success', 'Grupo registrado correctamente');
    }


    public function update(Request $request, Grupo $grupo)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if (!in_array($rol, ['Administrador', 'SuperAdministrador', 'Admin'])) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'sigla_grupo' => 'required|string|unique:grupo,sigla_grupo,' . $grupo->id_grupo . ',id_grupo',
            'id_carrera' => 'required|integer|exists:carrera,id_carrera',
            'id_modalidad' => 'required|integer|exists:modalidad,id_modalidad',
            'id_turno' => 'required|integer|exists:turno,id_turno',
            'capacidad_max' => 'required|integer|min:1',
            'estado' => 'required|string|in:Activo,Inactivo',
            'descripcion' => 'nullable|string',
        ]);

      
        if ($request->capacidad_max < $grupo->cant_estudiantes) {
            return redirect()->back()->withErrors([
                'capacidad_max' => "La capacidad máxima ({$request->capacidad_max}) no puede ser menor a la cantidad actual de estudiantes asignados ({$grupo->cant_estudiantes})."
            ])->withInput();
        }

        $grupo->update([
            'sigla_grupo' => $request->sigla_grupo,
            'id_carrera' => $request->id_carrera,
            'id_modalidad' => $request->id_modalidad,
            'id_turno' => $request->id_turno,
            'capacidad_max' => $request->capacidad_max,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion,
        ]);

        $this->registrarBitacora('Editar Grupo', 'Grupo editado: ' . $grupo->sigla_grupo);

        return redirect()->back()->with('success', 'Grupo actualizado correctamente');
    }

   
    public function destroy(Grupo $grupo)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if (!in_array($rol, ['Administrador', 'SuperAdministrador', 'Admin'])) {
            abort(403, 'No autorizado');
        }

     
        if ($grupo->cant_estudiantes > 0) {
            return redirect()->back()->withErrors([
                'grupo' => "No se puede eliminar el grupo {$grupo->sigla_grupo} porque tiene {$grupo->cant_estudiantes} estudiantes asignados."
            ]);
        }

        $sigla = $grupo->sigla_grupo;
        $grupo->delete();

        $this->registrarBitacora('Eliminar Grupo', 'Grupo eliminado: ' . $sigla);

        return redirect()->back()->with('success', 'Grupo eliminado correctamente');
    }

    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo' => 'Gestión de Grupos',
            'descripcion' => $descripcion,
            'estado' => 'Exitoso',
            'id_usuario' => Auth::user()->id_usuario
        ]);

        DetalleBitacora::create([
            'id_bitacora' => $bitacora->id_bitacora,
            'direccion_ip' => request()->ip(),
            'hora_inicio' => now()->toTimeString(),
            'accion' => $accion
        ]);
    }

    private function ensureDatabaseSeeded()
    {
      
        if (!\Illuminate\Support\Facades\Schema::hasColumn('grupo', 'id_carrera')) {
            \Illuminate\Support\Facades\Schema::table('grupo', function ($table) {
                $table->integer('id_carrera')->nullable();
                $table->text('descripcion')->nullable();
            });
        }

    
        if (Carrera::count() == 0) {
            Carrera::insert([
                ['nombre_carrera' => 'Ingeniería de Sistemas', 'descripcion' => 'Facultad de Ingeniería', 'duracion_anios' => 5],
                ['nombre_carrera' => 'Ciencias de la Educación', 'descripcion' => 'Facultad de Humanidades', 'duracion_anios' => 4],
                ['nombre_carrera' => 'Ciencias de la Computación', 'descripcion' => 'Facultad de Ingeniería', 'duracion_anios' => 5],
            ]);
        }

   
        if (Modalidad::count() == 0) {
            Modalidad::insert([
                ['nombre_modalidad' => 'Presencial', 'descripcion' => 'Clases en aula física'],
                ['nombre_modalidad' => 'Virtual', 'descripcion' => 'Clases en plataforma digital'],
                ['nombre_modalidad' => 'Semipresencial', 'descripcion' => 'Clases mixtas'],
            ]);
        }

   
        if (Turno::count() == 0) {
            Turno::insert([
                ['nombre_turno' => 'Mañana', 'hora_inicio' => '07:00:00', 'hora_fin' => '12:00:00'],
                ['nombre_turno' => 'Tarde', 'hora_inicio' => '14:00:00', 'hora_fin' => '18:00:00'],
                ['nombre_turno' => 'Noche', 'hora_inicio' => '18:30:00', 'hora_fin' => '22:00:00'],
            ]);
        }

     
        if (Aula::count() == 0) {
            Aula::insert([
                ['codigo_aula' => 'Aula 101', 'capacidad' => 40, 'ubicacion' => 'Módulo 225, Piso 1'],
                ['codigo_aula' => 'Aula 102', 'capacidad' => 45, 'ubicacion' => 'Módulo 225, Piso 1'],
                ['codigo_aula' => 'Aula 201', 'capacidad' => 35, 'ubicacion' => 'Módulo 225, Piso 2'],
            ]);
        }

       
        if (Gestion::count() == 0) {
            Gestion::insert([
                ['anio' => '2026', 'periodo' => 'Primer Semestre', 'fecha_inicio' => '2026-02-01', 'fecha_fin' => '2026-06-30'],
                ['anio' => '2026', 'periodo' => 'Segundo Semestre', 'fecha_inicio' => '2026-08-01', 'fecha_fin' => '2026-12-31'],
            ]);
        }

 
        if (Docente::count() == 0) {
            $persona = \App\Models\Persona::where('ci', '55555')->first();
            if (!$persona) {
                $persona = \App\Models\Persona::create([
                    'ci' => '55555',
                    'nombre' => 'Paola',
                    'apellido' => 'Limon',
                    'fecha_nacimiento' => '1985-05-04',
                    'telefono' => '5255552',
                    'correo' => 'paolalimon@gmail.com',
                ]);
            }
            Docente::create([
                'id_docente' => $persona->id_persona,
                'anio_servicio' => 5,
                'estado' => 'Activo'
            ]);
        }


        if (Grupo::count() == 0) {
            $carreras = Carrera::all();
            $modalidades = Modalidad::all();
            $turnos = Turno::all();
            $aulas = Aula::all();
            $gestiones = Gestion::all();
            $docentes = Docente::all();

            if ($carreras->isNotEmpty() && $modalidades->isNotEmpty() && $turnos->isNotEmpty() && $aulas->isNotEmpty() && $gestiones->isNotEmpty() && $docentes->isNotEmpty()) {
                Grupo::create([
                    'sigla_grupo' => 'Grupo A - Ingeniería',
                    'capacidad_max' => 40,
                    'estado' => 'Activo',
                    'cant_estudiantes' => 32,
                    'id_aula' => $aulas->first()->id_aula,
                    'id_modalidad' => $modalidades->first()->id_modalidad,
                    'id_turno' => $turnos->first()->id_turno,
                    'id_docente' => $docentes->first()->id_docente,
                    'id_gestion' => $gestiones->first()->id_gestion,
                    'id_carrera' => $carreras->first()->id_carrera,
                    'descripcion' => 'Grupo A para Ingeniería de Sistemas'
                ]);

                Grupo::create([
                    'sigla_grupo' => 'Grupo B - Ingeniería',
                    'capacidad_max' => 40,
                    'estado' => 'Activo',
                    'cant_estudiantes' => 28,
                    'id_aula' => $aulas->skip(1)->first()->id_aula ?? $aulas->first()->id_aula,
                    'id_modalidad' => $modalidades->first()->id_modalidad,
                    'id_turno' => $turnos->skip(1)->first()->id_turno ?? $turnos->first()->id_turno,
                    'id_docente' => $docentes->first()->id_docente,
                    'id_gestion' => $gestiones->first()->id_gestion,
                    'id_carrera' => $carreras->first()->id_carrera,
                    'descripcion' => 'Grupo B para Ingeniería de Sistemas'
                ]);

                Grupo::create([
                    'sigla_grupo' => 'Grupo C - Ciencias',
                    'capacidad_max' => 35,
                    'estado' => 'Activo',
                    'cant_estudiantes' => 15,
                    'id_aula' => $aulas->first()->id_aula,
                    'id_modalidad' => $modalidades->skip(1)->first()->id_modalidad ?? $modalidades->first()->id_modalidad,
                    'id_turno' => $turnos->first()->id_turno,
                    'id_docente' => $docentes->first()->id_docente,
                    'id_gestion' => $gestiones->first()->id_gestion,
                    'id_carrera' => $carreras->skip(1)->first()->id_carrera ?? $carreras->first()->id_carrera,
                    'descripcion' => 'Grupo C para Ciencias de la Educación'
                ]);
            }
        }
    }
}