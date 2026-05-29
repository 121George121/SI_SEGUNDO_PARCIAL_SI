<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Persona;
use App\Models\Inscripcion;
use App\Models\InscripcionCarrera;
use App\Models\Carrera;
use App\Models\Documento;
use App\Models\Pago;
use App\Models\ResultadoAcademico;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Grupo;
use App\Helpers\LoggerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PostulanteController extends Controller
{
    // Retrieve currently logged-in Postulante's profile
    protected function getPostulante()
    {
        return Postulante::with(['persona', 'asignacion.carrera', 'asignacion.gestion'])
                         ->where('id_postulante', Auth::user()->id_persona)
                         ->firstOrFail();
    }

    // Postulante Dashboard - Multi-tab Wizard View
    public function dashboard()
    {
        $postulante = $this->getPostulante();
        $persona = $postulante->persona;

        // Get latest active enrollment (INSCRIPCION)
        $inscripcion = Inscripcion::with('carreras')->where('id_postulante', $postulante->id_postulante)->first();

        // All available careers for dropdown selection
        $carreras = Carrera::all();

        // Get postulante's documents
        $documentos = Documento::where('id_postulante', $postulante->id_postulante)->get();

        // Get enrollment payments
        $pagos = $inscripcion ? Pago::with('comprobante')->where('id_inscripcion', $inscripcion->id_inscripcion)->get() : collect();

        // Get registered grades
        $notas = Nota::with(['evaluacion.materia', 'grupo'])
                     ->where('id_postulante', $postulante->id_postulante)
                     ->get();

        // Get final GPA
        $resultado = ResultadoAcademico::where('id_postulante', $postulante->id_postulante)->first();

        // Get group details if assigned
        $grupoAsignado = $postulante->grupos()->with(['aula', 'turno', 'docente.persona'])->first();

        // Get attendance count
        $asistencias = Asistencia::with('materia')
                                 ->where('id_postulante', $postulante->id_postulante)
                                 ->get();

        return view('dashboard.postulante', compact(
            'postulante', 'persona', 'inscripcion', 'carreras', 'documentos', 'pagos', 'notas', 'resultado', 'grupoAsignado', 'asistencias'
        ));
    }

    // Update personal details (CI, name, etc.)
    public function updatePersona(Request $request)
    {
        $postulante = $this->getPostulante();
        $persona = $postulante->persona;

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ]);

        $persona->update($request->all());

        LoggerHelper::log('UPDATE', 'Postulante actualizó datos personales', "Postulante CI: {$persona->ci}");
        return back()->with('success', 'Datos personales actualizados con éxito.');
    }

    // Submit Document
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string|max:50',
            'documento_file' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120', // max 5MB
        ]);

        $postulante = $this->getPostulante();

        // Handle file upload
        if ($request->hasFile('documento_file')) {
            $file = $request->file('documento_file');
            
            // In a real environment, we'd store in disk and save path.
            // Since this is local, we store in public disk to make it easy to view/access
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/documents'), $filename);

            Documento::create([
                'tipo_documento' => $request->input('tipo_documento'),
                'nombre' => $filename,
                'estado' => 'Pendiente',
                'observacion' => 'Documento en espera de revisión por el Administrador.',
                'fecha_registro' => now()->toDateString(),
                'id_administrador' => null,
                'id_postulante' => $postulante->id_postulante,
            ]);

            LoggerHelper::log('CREATE', 'Postulante subió documento', "Tipo: {$request->input('tipo_documento')}");
            return back()->with('success', 'Documento subido con éxito y enviado para revisión.');
        }

        return back()->withErrors(['documento_file' => 'Error al cargar el archivo del documento.']);
    }

    // Submit Payment Proof
    public function registerPayment(Request $request)
    {
        $request->validate([
            'pago_id' => 'required|integer|exists:pago,id_pago',
            'metodo_pago' => 'required|string|max:50',
            'monto' => 'required|numeric|min:1',
            'referencia' => 'required|string|max:100',
            'comprobante_img' => 'required|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
        ]);

        $postulante = $this->getPostulante();
        $pago = Pago::findOrFail($request->input('pago_id'));

        // Handle image upload
        if ($request->hasFile('comprobante_img')) {
            $file = $request->file('comprobante_img');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/payments'), $filename);

            // Update Pago details
            $pago->update([
                'monto' => $request->input('monto'),
                'metodo_pago' => $request->input('metodo_pago'),
                'estado_pago' => 'Pendiente', // back to pending for admin validation
                'observaciones' => "Nro. Referencia/Comprobante: {$request->input('referencia')}. Adjunto: {$filename}",
            ]);

            LoggerHelper::log('UPDATE', 'Postulante registró comprobante de pago', "Pago ID: {$pago->id_pago}");
            return back()->with('success', 'Comprobante de pago registrado. En espera de aprobación por el Administrador.');
        }

        return back()->withErrors(['comprobante_img' => 'Error al cargar la imagen del comprobante.']);
    }

    // Manage/Add/Change Career Priority
    public function changeCareer(Request $request)
    {
        $request->validate([
            'carrera_id' => 'required|integer|exists:carrera,id_carrera',
            'inscripcion_id' => 'required|integer|exists:inscripcion,id_inscripcion',
        ]);

        DB::beginTransaction();
        try {
            // Delete previous selections or set them to Inactivo
            InscripcionCarrera::where('id_inscripcion', $request->inscripcion_id)->delete();

            // Insert new selection
            InscripcionCarrera::create([
                'id_inscripcion' => $request->inscripcion_id,
                'id_carrera' => $request->carrera_id,
                'prioridad' => '1',
                'estado' => 'Activo',
            ]);

            DB::commit();
            LoggerHelper::log('UPDATE', 'Postulante modificó preferencia de carrera', "Carrera ID: {$request->carrera_id}");
            return back()->with('success', 'Preferencia de carrera seleccionada con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar preferencia de carrera: ' . $e->getMessage()]);
        }
    }
}
