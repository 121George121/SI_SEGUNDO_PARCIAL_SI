<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\InscripcionCarrera;
use App\Models\Postulante;
use App\Models\Persona;
use App\Models\User;
use App\Models\Rol;
use App\Models\Carrera;
use App\Models\Documento;
use App\Models\Pago;
use App\Models\Comprobante;
use App\Helpers\LoggerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    // --- CU03: List Inscriptions (Admin & SuperAdmin) ---
    public function index(Request $request)
    {
        $query = Inscripcion::with(['postulante.persona', 'postulante.documentos', 'carreras', 'pagos']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);
            $query->whereHas('postulante.persona', function($q) use ($search) {
                $q->where(DB::raw('lower(nombre)'), 'like', "%{$search}%")
                  ->orWhere(DB::raw('lower(apellido)'), 'like', "%{$search}%")
                  ->orWhere('ci', 'like', "%{$search}%");
            })->orWhere('codigo_inscripcion', 'like', "%{$search}%");
        }

        $inscripciones = $query->orderBy('id_inscripcion', 'desc')->get();
        $carreras = Carrera::all();

        return view('admin.inscripciones', compact('inscripciones', 'carreras'));
    }

    // --- CU03: Detail of Selected Inscription (Admin & SuperAdmin) ---
    public function detail($id)
    {
        $inscripcion = Inscripcion::with(['postulante.persona', 'carreras', 'pagos.comprobante'])->findOrFail($id);
        $postulante = $inscripcion->postulante;
        $persona = $postulante->persona;

        // Documentos del postulante ya cargados en BD
        $dbDocumentos = Documento::where('id_postulante', $postulante->id_postulante)->get();

        // Lista de requisitos dinámica: tomar de los documentos creados en "Gestionar Documentos"
        // (aquellos sin id_postulante asociado = son requisitos generales del sistema)
        $requisitos = Documento::whereNull('id_postulante')
            ->orderBy('tipo_documento')
            ->orderBy('id_documento')
            ->get();

        // Si no hay requisitos configurados en la BD, usar lista base mínima
        if ($requisitos->isEmpty()) {
            $requisitos = collect([
                (object)['nombre' => 'Cédula de Identidad',    'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Diploma de Bachiller',   'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Certificado de Nacimiento', 'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Fotografía 3x4',         'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Certificado de Notas',   'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Currículum Vitae',       'tipo_documento' => 'Opcional'],
                (object)['nombre' => 'Certificado de Idiomas', 'tipo_documento' => 'Opcional'],
            ]);
        }

        // Mapear requisitos con el estado real del postulante
        $documentos = collect();
        foreach ($requisitos as $req) {
            // Buscar por nombre del documento
            $matched = $dbDocumentos->first(function($d) use ($req) {
                return stripos($d->tipo_documento, $req->nombre) !== false
                    || stripos($d->nombre, $req->nombre) !== false
                    || $d->tipo_documento === $req->nombre;
            });

            $esObligatorio = in_array($req->tipo_documento, ['Obligatorio', 'Legal', 'Académico']);

            $documentos->push((object)[
                'id_documento'   => $matched ? $matched->id_documento : null,
                'nombre'         => $req->nombre,
                'archivo'        => $matched ? $matched->nombre : null,
                'tipo_documento' => $req->nombre,
                'tipo'           => $req->tipo_documento,
                'obligatorio'    => $esObligatorio ? 'Sí' : 'No',
                'estado'         => $matched ? $matched->estado : 'Pendiente',
                'fecha_carga'    => $matched ? $matched->fecha_registro : null,
                'fecha_validacion' => $matched ? $matched->fecha_validacion : null,
                'observacion'    => $matched ? $matched->observacion : '',
            ]);
        }

        // Get Carrera Principal and Carrera Secundaria
        $carreraPrincipal = $inscripcion->carreras->where('pivot.prioridad', 1)->first();
        $carreraSecundaria = $inscripcion->carreras->where('pivot.prioridad', 2)->first();

        // Get latest active payment
        $pago = $inscripcion->pagos->sortByDesc('id_pago')->first();

        // All available careers for editing
        $allCarreras = Carrera::all();

        return view('admin.inscripciones_detalle', compact(
            'inscripcion', 'postulante', 'persona', 'documentos',
            'carreraPrincipal', 'carreraSecundaria', 'pago', 'allCarreras'
        ));
    }

    // --- CU03: Register new Inscription (Admin & SuperAdmin) ---
    public function store(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|unique:persona,ci',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'correo' => 'required|email|unique:usuario,correo|unique:persona,correo',
            'carrera_principal_id' => 'required|integer|exists:carrera,id_carrera',
            'carrera_secundaria_id' => 'required|integer|exists:carrera,id_carrera|different:carrera_principal_id',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Persona
            $persona = Persona::create([
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'correo' => strtolower($request->correo),
            ]);

            // 2. Create User account for Postulante
            $rol = Rol::where('nombre_rol', 'Postulante')->first();
            $user = User::create([
                'nombre_usuario' => 'pos_' . $request->ci,
                'correo' => strtolower($request->correo),
                'contraseña' => Hash::make($request->ci), // Default password is CI
                'estado' => 'Activo',
                'fecha_creacion' => now()->toDateString(),
                'id_rol' => $rol->id_rol,
                'id_persona' => $persona->id_persona,
            ]);

            // 3. Create Postulante profile
            $postulante = Postulante::create([
                'id_postulante' => $persona->id_persona,
                'estado_inscripcion' => 'Pendiente',
                'fecha_registro' => now()->toDateString(),
                'id_asignacion' => null,
            ]);

            // 4. Create Inscription record
            $codigoInsc = 'POS-' . date('Y') . '-' . sprintf('%04d', $postulante->id_postulante);
            $inscripcion = Inscripcion::create([
                'codigo_inscripcion' => $codigoInsc,
                'estado' => 'Activo',
                'fecha_inscripcion' => now()->toDateString(),
                'id_postulante' => $postulante->id_postulante,
            ]);

            // 5. Associate Careers with priorities
            InscripcionCarrera::create([
                'id_inscripcion' => $inscripcion->id_inscripcion,
                'id_carrera' => $request->carrera_principal_id,
                'prioridad' => 1,
                'estado' => 'Pendiente'
            ]);

            InscripcionCarrera::create([
                'id_inscripcion' => $inscripcion->id_inscripcion,
                'id_carrera' => $request->carrera_secundaria_id,
                'prioridad' => 2,
                'estado' => 'Pendiente'
            ]);

            // Automatically mock initial expected documents to simulate realistic data
            $initialDocs = ['Cédula de Identidad', 'Diploma de Bachiller', 'Certificado de Nacimiento', 'Fotografía 3x4'];
            foreach ($initialDocs as $docName) {
                Documento::create([
                    'tipo_documento' => $docName,
                    'nombre' => 'Documento_' . str_replace(' ', '_', $docName) . '.pdf',
                    'estado' => 'Pendiente',
                    'observacion' => null,
                    'fecha_registro' => now()->toDateString(),
                    'id_postulante' => $postulante->id_postulante,
                ]);
            }

            DB::commit();
            LoggerHelper::log('CREATE', 'Inscripción de Postulante creada', "Inscripción: {$codigoInsc}");
            return back()->with('success', 'Inscripción del postulante registrada con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al registrar inscripción: ' . $e->getMessage()]);
        }
    }

    // --- CU03: Modify Inscription details (Admin & SuperAdmin) ---
    public function update(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $postulante = $inscripcion->postulante;
        $persona = $postulante->persona;

        $request->validate([
            'ci' => 'required|string|unique:persona,ci,' . $persona->id_persona . ',id_persona',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'correo' => 'required|email|unique:usuario,correo,' . $persona->user->id_usuario . ',id_usuario|unique:persona,correo,' . $persona->id_persona . ',id_persona',
            'carrera_principal_id' => 'required|integer|exists:carrera,id_carrera',
            'carrera_secundaria_id' => 'required|integer|exists:carrera,id_carrera|different:carrera_principal_id',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update Persona
            $persona->update([
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'correo' => strtolower($request->correo),
            ]);

            // 2. Update User Account
            $persona->user->update([
                'correo' => strtolower($request->correo),
            ]);

            // 3. Re-map careers
            InscripcionCarrera::where('id_inscripcion', $inscripcion->id_inscripcion)->delete();

            InscripcionCarrera::create([
                'id_inscripcion' => $inscripcion->id_inscripcion,
                'id_carrera' => $request->carrera_principal_id,
                'prioridad' => 1,
                'estado' => 'Pendiente'
            ]);

            InscripcionCarrera::create([
                'id_inscripcion' => $inscripcion->id_inscripcion,
                'id_carrera' => $request->carrera_secundaria_id,
                'prioridad' => 2,
                'estado' => 'Pendiente'
            ]);

            DB::commit();
            LoggerHelper::log('UPDATE', 'Inscripción de Postulante modificada', "Inscripción ID: {$inscripcion->id_inscripcion}");
            return back()->with('success', 'Inscripción del postulante actualizada con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al modificar inscripción: ' . $e->getMessage()]);
        }
    }

    // --- CU03: Delete Inscription (Admin & SuperAdmin) ---
    public function destroy($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        $codigo = $inscripcion->codigo_inscripcion;
        $postulante = $inscripcion->postulante;
        $persona = $postulante->persona;

        DB::beginTransaction();
        try {
            // Delete payments and career priorities
            $inscripcion->pagos()->delete();
            InscripcionCarrera::where('id_inscripcion', $inscripcion->id_inscripcion)->delete();
            Documento::where('id_postulante', $postulante->id_postulante)->delete();
            
            // Delete primary registration records
            $inscripcion->delete();
            $postulante->delete();
            
            if ($persona->user) {
                $persona->user->delete();
            }
            $persona->delete();

            DB::commit();
            LoggerHelper::log('DELETE', 'Inscripción de Postulante eliminada', "Inscripción: {$codigo}");
            return redirect()->route('admin.inscripciones')->with('success', 'Inscripción y postulante eliminados con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al eliminar la inscripción: ' . $e->getMessage()]);
        }
    }

    // --- CU03: Validate Inscription Data (Admin & SuperAdmin) ---
    public function validateData($id)
    {
        $inscripcion = Inscripcion::findOrFail($id);
        
        $inscripcion->update([
            'estado' => 'Validado'
        ]);

        LoggerHelper::log('PROCESS', 'Inscripción validada administrativamente', "Inscripción ID: {$id}");
        return back()->with('success', 'Los datos de la inscripción han sido validados con éxito.');
    }

    // --- CU03: Generate Pending Payment Order (Admin & SuperAdmin) ---
    public function generatePayment(Request $request, $id)
    {
        $inscripcion = Inscripcion::findOrFail($id);

        // Check if there is an existing payment
        $existingPayment = Pago::where('id_inscripcion', $inscripcion->id_inscripcion)->first();
        if ($existingPayment) {
            return back()->withErrors(['error' => 'Ya existe un comprobante de pago generado para esta inscripción.']);
        }

        Pago::create([
            'monto' => 350.00,
            'fecha_pago' => null,
            'metodo_pago' => 'Transferencia',
            'estado_pago' => 'Pendiente',
            'observaciones' => 'Orden de pago de inscripción generada por administración.',
            'id_comprobante' => null,
            'id_inscripcion' => $inscripcion->id_inscripcion
        ]);

        LoggerHelper::log('PROCESS', 'Orden de pago generada', "Inscripción ID: {$id}, Monto: 350.00 Bs");
        return back()->with('success', 'Orden de pago de 350.00 Bs generada correctamente.');
    }

    // --- CU04: Validate specific document (Admin & SuperAdmin) ---
    public function validateDocumentDetails(Request $request, $id)
    {
        $request->validate([
            'estado'           => 'required|string|in:Validado,Rechazado,Pendiente',
            'observacion'      => 'nullable|string',
            'documento_nombre' => 'required|string',
            'id_postulante'    => 'required|integer',
        ]);

        // Resolver el id_administrador de forma segura
        $idAdmin = null;
        $adminRecord = \App\Models\Administrador::find(Auth::user()->id_persona);
        if ($adminRecord) {
            $idAdmin = $adminRecord->id_administrador;
        } else {
            $primerAdmin = \App\Models\Administrador::first();
            if ($primerAdmin) $idAdmin = $primerAdmin->id_administrador;
        }

        // Fecha de validación automática cuando se valida/aprueba
        $fechaValidacion = ($request->estado === 'Validado')
            ? now()->toDateString()
            : null;

        // Buscar documento existente del postulante
        $documento = Documento::where('id_postulante', $request->id_postulante)
            ->where(function($q) use ($request) {
                $q->where('tipo_documento', $request->documento_nombre)
                  ->orWhere('nombre', 'like', '%' . $request->documento_nombre . '%');
            })
            ->first();

        if (!$documento) {
            // Crear nuevo registro para este postulante ligado al requisito
            $documento = Documento::create([
                'tipo_documento'   => $request->documento_nombre,
                'nombre'           => 'Documento_' . str_replace(' ', '_', $request->documento_nombre) . '.pdf',
                'estado'           => $request->estado,
                'observacion'      => $request->observacion,
                'fecha_registro'   => now()->toDateString(),
                'fecha_validacion' => $fechaValidacion,
                'id_postulante'    => $request->id_postulante,
                'id_administrador' => $idAdmin,
            ]);
        } else {
            $updateData = [
                'estado'           => $request->estado,
                'observacion'      => $request->observacion,
                'id_administrador' => $idAdmin,
            ];
            // Solo sobreescribir fecha_validacion si se está validando
            if ($fechaValidacion) {
                $updateData['fecha_validacion'] = $fechaValidacion;
            } else {
                $updateData['fecha_validacion'] = null;
            }
            $documento->update($updateData);
        }

        LoggerHelper::log('PROCESS', 'Estado de documento actualizado', "Doc ID {$documento->id_documento} -> {$request->estado}");
        return back()->with('success', 'Estado del documento actualizado con éxito.');
    }

    // --- CU03: View Progress Timeline (Postulante read-only view) ---
    public function viewProgress()
    {
        $loggedUser = Auth::user();
        if (!$loggedUser->isPostulante()) {
            return redirect()->route('dashboard');
        }

        $postulante = Postulante::with(['persona', 'asignacion.carrera'])
                                 ->where('id_postulante', $loggedUser->id_persona)
                                 ->firstOrFail();

        $persona = $postulante->persona;
        $inscripcion = Inscripcion::with(['carreras', 'pagos.comprobante'])->where('id_postulante', $postulante->id_postulante)->first();

        // Get postulante's documents from DB
        $dbDocumentos = Documento::where('id_postulante', $postulante->id_postulante)->get();

        // Lista de requisitos desde "Gestionar Documentos" (sin id_postulante = son requisitos generales)
        $requisitos = Documento::whereNull('id_postulante')
            ->orderBy('tipo_documento')
            ->orderBy('id_documento')
            ->get();

        if ($requisitos->isEmpty()) {
            $requisitos = collect([
                (object)['nombre' => 'Cédula de Identidad',    'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Diploma de Bachiller',   'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Certificado de Nacimiento', 'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Fotografía 3x4',         'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Certificado de Notas',   'tipo_documento' => 'Obligatorio'],
                (object)['nombre' => 'Currículum Vitae',       'tipo_documento' => 'Opcional'],
                (object)['nombre' => 'Certificado de Idiomas', 'tipo_documento' => 'Opcional'],
            ]);
        }

        $documentos = collect();
        foreach ($requisitos as $req) {
            $matched = $dbDocumentos->first(function($d) use ($req) {
                return stripos($d->tipo_documento, $req->nombre) !== false
                    || stripos($d->nombre, $req->nombre) !== false
                    || $d->tipo_documento === $req->nombre;
            });
            $esObligatorio = in_array($req->tipo_documento, ['Obligatorio', 'Legal', 'Académico']);
            $documentos->push((object)[
                'nombre'      => $req->nombre,
                'archivo'     => $matched ? $matched->nombre : null,
                'tipo'        => $req->tipo_documento,
                'obligatorio' => $esObligatorio ? 'Sí' : 'No',
                'estado'      => $matched ? $matched->estado : 'Pendiente',
                'fecha_carga' => $matched ? $matched->fecha_registro : null,
                'fecha_validacion' => $matched ? $matched->fecha_validacion : null,
            ]);
        }

        $carreraPrincipal  = $inscripcion ? $inscripcion->carreras->where('pivot.prioridad', 1)->first() : null;
        $carreraSecundaria = $inscripcion ? $inscripcion->carreras->where('pivot.prioridad', 2)->first() : null;
        $pago = $inscripcion ? $inscripcion->pagos->sortByDesc('id_pago')->first() : null;

        return view('postulante.progreso', compact(
            'postulante', 'persona', 'inscripcion', 'documentos',
            'carreraPrincipal', 'carreraSecundaria', 'pago'
        ));
    }
}
