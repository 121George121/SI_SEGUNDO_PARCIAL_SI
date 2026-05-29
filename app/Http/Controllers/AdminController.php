<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Docente;
use App\Models\Postulante;
use App\Models\Carrera;
use App\Models\Gestion;
use App\Models\Aula;
use App\Models\Modalidad;
use App\Models\Turno;
use App\Models\Horario;
use App\Models\Materia;
use App\Models\Grupo;
use App\Models\Evaluacion;
use App\Models\Nota;
use App\Models\Asistencia;
use App\Models\Documento;
use App\Models\Administrador;
use App\Models\Pago;
use App\Models\Comprobante;
use App\Models\CupoCarrera;
use App\Models\Bitacora;
use App\Helpers\LoggerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Admin Dashboard - View KPIs and quick actions
    public function dashboard()
    {
        $kpis = [
            'postulantes'          => Postulante::count(),
            'docentes'             => Docente::count(),
            'grupos'               => Grupo::count(),
            'recaudado'            => Pago::where('estado_pago', 'Pagado')->sum('monto'),
            'pagos_pendientes'     => Pago::where('estado_pago', 'Pendiente')->count(),
            'documentos_pendientes'=> Documento::where('estado', 'Pendiente')->count(),
        ];

        // Estadísticas reales del proceso de admisión
        $stats_admision = [
            'docs_aprobados'  => Documento::where('estado', 'Validado')->count(),
            'pagos_validados' => Pago::where('estado_pago', 'Pagado')->count(),
            'admitidos'       => Postulante::where('estado_inscripcion', 'Admitido')->count(),
        ];

        // Postulantes por carrera (desde inscripcion_carrera → carrera)
        $postulantes_carrera = DB::table('inscripcion_carrera')
            ->join('carrera', 'inscripcion_carrera.id_carrera', '=', 'carrera.id_carrera')
            ->select('carrera.nombre_carrera', DB::raw('count(*) as total'))
            ->where('inscripcion_carrera.prioridad', '1')
            ->groupBy('carrera.nombre_carrera')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $carreras = Carrera::all();
        $gestiones = Gestion::all();
        $ultimos_postulantes = Postulante::with('persona')->orderBy('id_postulante', 'desc')->take(5)->get();

        return view('dashboard.admin', compact(
            'kpis', 'stats_admision', 'postulantes_carrera',
            'carreras', 'gestiones', 'ultimos_postulantes'
        ));
    }

    // --- CRUD USUARIOS & PERSONAS ---
    public function indexUsuarios()
    {
        $usuarios = User::with(['persona.administrador', 'persona.docente', 'persona.postulante', 'rol'])->get();
        $personas = Persona::all();
        $roles = Rol::all();
        return view('admin.usuarios', compact('usuarios', 'personas', 'roles'));
    }

    public function storeUsuario(Request $request)
    {
        // 1. Basic common fields validation rules
        $rules = [
            'id_rol' => 'required|integer|exists:rol,id_rol',
            'ci' => 'required|string|unique:persona,ci',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'correo' => 'required|email|unique:usuario,correo|unique:persona,correo',
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario',
            'contraseña' => 'required|string|min:8',
        ];

        // Fetch targeted role and check current user permissions
        $targetRol = Rol::findOrFail($request->id_rol);
        $loggedUser = Auth::user();

        // 2. Role-based Privilege Validation
        if ($loggedUser->rol->nombre_rol === 'Admin') {
            if ($targetRol->nombre_rol === 'SuperAdministrador' || $targetRol->nombre_rol === 'Admin') {
                return back()->withErrors(['error' => 'Error de privilegios: Un Administrador no tiene permisos para registrar Superadministradores o Administradores.']);
            }
        } elseif ($loggedUser->rol->nombre_rol === 'Docente') {
            return back()->withErrors(['error' => 'Error de privilegios: Los docentes no tienen permitido registrar nuevos usuarios.']);
        }

        // 3. Conditional validation rules based on target role
        if ($targetRol->nombre_rol === 'SuperAdministrador' || $targetRol->nombre_rol === 'Admin') {
            $rules['telefono'] = 'nullable|string|max:20';
            $rules['direccion'] = 'nullable|string';
            $rules['cargo'] = 'nullable|string|max:100';
            $rules['area'] = 'nullable|string|max:100';
        } elseif ($targetRol->nombre_rol === 'Docente') {
            $rules['telefono'] = 'nullable|string|max:20';
            $rules['direccion'] = 'nullable|string';
        } elseif ($targetRol->nombre_rol === 'Postulante') {
            // Postulantes only require the essential common fields
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Create persona with only fields applicable to selected role
            $personaData = [
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'correo' => strtolower($request->correo),
            ];

            if ($targetRol->nombre_rol !== 'Postulante') {
                $personaData['telefono'] = $request->telefono;
            } else {
                $personaData['telefono'] = null;
            }

            if ($targetRol->nombre_rol === 'SuperAdministrador' || $targetRol->nombre_rol === 'Admin') {
                $personaData['direccion'] = $request->direccion;
            } else {
                $personaData['direccion'] = null;
            }

            $persona = Persona::create($personaData);

            // Create user
            $user = User::create([
                'nombre_usuario' => $request->nombre_usuario,
                'correo' => strtolower($request->correo),
                'contraseña' => Hash::make($request->contraseña),
                'estado' => 'Activo',
                'fecha_creacion' => now()->toDateString(),
                'id_rol' => $request->id_rol,
                'id_persona' => $persona->id_persona,
            ]);

            // Create role details depending on selected role
            if ($targetRol->nombre_rol === 'SuperAdministrador') {
                DB::table('superadministrador')->insert([
                    'id_superadministrador' => $persona->id_persona,
                    'cargo' => $request->cargo ?? 'SuperAdministrador',
                    'estado' => 'Activo'
                ]);
            } elseif ($targetRol->nombre_rol === 'Admin') {
                DB::table('administrador')->insert([
                    'id_administrador' => $persona->id_persona,
                    'cargo' => $request->cargo ?? 'Administrador',
                    'area' => $request->area ?? 'Registro',
                    'estado' => 'Activo'
                ]);
            } elseif ($targetRol->nombre_rol === 'Docente') {
                Docente::create([
                    'id_docente' => $persona->id_persona,
                    'anio_servicio' => 1,
                    'estado' => 'Activo'
                ]);
            } elseif ($targetRol->nombre_rol === 'Postulante') {
                Postulante::create([
                    'id_postulante' => $persona->id_persona,
                    'estado_inscripcion' => 'Pendiente',
                    'fecha_registro' => now()->toDateString(),
                    'id_asignacion' => null,
                ]);
            }

            DB::commit();
            LoggerHelper::log('CREATE', 'Usuario creado', "Usuario: {$user->nombre_usuario} con rol {$targetRol->nombre_rol}");
            return back()->with('success', 'Usuario registrado con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al crear usuario: ' . $e->getMessage()]);
        }
    }

    public function updateUsuario(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $persona = $user->persona;
        $loggedUser = Auth::user();

        // 1. Privilege Validation (Logged-in user vs. Target user current role)
        $oldRol = $user->rol;
        if ($loggedUser->rol->nombre_rol === 'Admin') {
            if ($oldRol->nombre_rol === 'SuperAdministrador' || $oldRol->nombre_rol === 'Admin') {
                return back()->withErrors(['error' => 'Error de privilegios: Un Administrador no tiene permisos para editar Superadministradores o Administradores.']);
            }
        } elseif ($loggedUser->rol->nombre_rol === 'Docente') {
            return back()->withErrors(['error' => 'Error de privilegios: Los docentes no tienen permitido editar usuarios.']);
        }

        // 2. Privilege Validation (Target role to assign)
        $targetRol = Rol::findOrFail($request->id_rol);
        if ($loggedUser->rol->nombre_rol === 'Admin') {
            if ($targetRol->nombre_rol === 'SuperAdministrador' || $targetRol->nombre_rol === 'Admin') {
                return back()->withErrors(['error' => 'Error de privilegios: Un Administrador no puede asignar el rol de Superadministrador o Administrador.']);
            }
        }

        // 3. Validation Rules
        $rules = [
            'id_rol' => 'required|integer|exists:rol,id_rol',
            'ci' => 'required|string|unique:persona,ci,' . $persona->id_persona . ',id_persona',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'correo' => 'required|email|unique:usuario,correo,' . $user->id_usuario . ',id_usuario|unique:persona,correo,' . $persona->id_persona . ',id_persona',
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario,' . $user->id_usuario . ',id_usuario',
            'contraseña' => 'nullable|string|min:8', // Optional on update
        ];

        // Conditional validation rules based on target role
        if ($targetRol->nombre_rol === 'SuperAdministrador' || $targetRol->nombre_rol === 'Admin') {
            $rules['telefono'] = 'nullable|string|max:20';
            $rules['direccion'] = 'nullable|string';
            $rules['cargo'] = 'nullable|string|max:100';
            $rules['area'] = 'nullable|string|max:100';
        } elseif ($targetRol->nombre_rol === 'Docente') {
            $rules['telefono'] = 'nullable|string|max:20';
            $rules['direccion'] = 'nullable|string';
        } elseif ($targetRol->nombre_rol === 'Postulante') {
            // Postulantes only require the essential common fields
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Update persona with only fields applicable to selected role
            $personaData = [
                'ci' => $request->ci,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'correo' => strtolower($request->correo),
            ];

            if ($targetRol->nombre_rol !== 'Postulante') {
                $personaData['telefono'] = $request->telefono;
            } else {
                $personaData['telefono'] = null;
            }

            if ($targetRol->nombre_rol === 'SuperAdministrador' || $targetRol->nombre_rol === 'Admin') {
                $personaData['direccion'] = $request->direccion;
            } else {
                $personaData['direccion'] = null;
            }

            $persona->update($personaData);

            // Update user
            $userData = [
                'nombre_usuario' => $request->nombre_usuario,
                'correo' => strtolower($request->correo),
                'id_rol' => $request->id_rol,
            ];

            if ($request->filled('contraseña')) {
                $userData['contraseña'] = Hash::make($request->contraseña);
            }

            $user->update($userData);

            // If the role changed, clean up previous role-specific tables and insert new role details
            if ($oldRol->id_rol != $targetRol->id_rol) {
                // Delete previous details
                DB::table('superadministrador')->where('id_superadministrador', $persona->id_persona)->delete();
                DB::table('administrador')->where('id_administrador', $persona->id_persona)->delete();
                Docente::where('id_docente', $persona->id_persona)->delete();
                Postulante::where('id_postulante', $persona->id_persona)->delete();

                // Create new details
                if ($targetRol->nombre_rol === 'SuperAdministrador') {
                    DB::table('superadministrador')->insert([
                        'id_superadministrador' => $persona->id_persona,
                        'cargo' => $request->cargo ?? 'SuperAdministrador',
                        'estado' => 'Activo'
                    ]);
                } elseif ($targetRol->nombre_rol === 'Admin') {
                    DB::table('administrador')->insert([
                        'id_administrador' => $persona->id_persona,
                        'cargo' => $request->cargo ?? 'Administrador',
                        'area' => $request->area ?? 'Registro',
                        'estado' => 'Activo'
                    ]);
                } elseif ($targetRol->nombre_rol === 'Docente') {
                    Docente::create([
                        'id_docente' => $persona->id_persona,
                        'anio_servicio' => 1,
                        'estado' => 'Activo'
                    ]);
                } elseif ($targetRol->nombre_rol === 'Postulante') {
                    Postulante::create([
                        'id_postulante' => $persona->id_persona,
                        'estado_inscripcion' => 'Pendiente',
                        'fecha_registro' => now()->toDateString(),
                        'id_asignacion' => null,
                    ]);
                }
            } else {
                // Role did not change, just update details if applicable
                if ($targetRol->nombre_rol === 'SuperAdministrador') {
                    DB::table('superadministrador')->where('id_superadministrador', $persona->id_persona)->update([
                        'cargo' => $request->cargo ?? 'SuperAdministrador'
                    ]);
                } elseif ($targetRol->nombre_rol === 'Admin') {
                    DB::table('administrador')->where('id_administrador', $persona->id_persona)->update([
                        'cargo' => $request->cargo ?? 'Administrador',
                        'area' => $request->area ?? 'Registro'
                    ]);
                }
            }

            DB::commit();
            LoggerHelper::log('UPDATE', 'Usuario editado', "Usuario: {$user->nombre_usuario} con rol {$targetRol->nombre_rol}");
            return back()->with('success', 'Usuario actualizado con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al actualizar usuario: ' . $e->getMessage()]);
        }
    }

    public function destroyUsuario($id)
    {
        $user = User::findOrFail($id);
        $username = $user->nombre_usuario;
        $personaId = $user->id_persona;

        // Privilege Validation
        $loggedUser = Auth::user();
        if ($loggedUser->rol->nombre_rol === 'Docente') {
            return back()->withErrors(['error' => 'Error de privilegios: Los docentes no tienen permitido eliminar usuarios.']);
        }

        DB::beginTransaction();
        try {
            // Delete role-specific child records first to ensure no PostgreSQL constraint violations
            DB::table('superadministrador')->where('id_superadministrador', $personaId)->delete();
            DB::table('administrador')->where('id_administrador', $personaId)->delete();
            Docente::where('id_docente', $personaId)->delete();
            Postulante::where('id_postulante', $personaId)->delete();

            $user->delete();
            Persona::destroy($personaId);
            DB::commit();

            LoggerHelper::log('DELETE', 'Usuario eliminado', "Usuario: {$username}");
            return back()->with('success', 'Usuario y persona eliminados con éxito.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al eliminar usuario: ' . $e->getMessage()]);
        }
    }

    // --- CRUD CARRERAS ---
    public function indexCarreras()
    {
        $carreras = Carrera::all();
        return view('admin.carreras', compact('carreras'));
    }

    public function storeCarrera(Request $request)
    {
        $request->validate([
            'nombre_carrera' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'duracion_anios' => 'required|integer|min:1',
        ]);

        $carrera = Carrera::create($request->all());
        LoggerHelper::log('CREATE', 'Carrera creada', "Carrera: {$carrera->nombre_carrera}");
        return back()->with('success', 'Carrera creada con éxito.');
    }

    public function updateCarrera(Request $request, $id)
    {
        $request->validate([
            'nombre_carrera' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'duracion_anios' => 'required|integer|min:1',
        ]);

        $carrera = Carrera::findOrFail($id);
        $carrera->update($request->all());
        LoggerHelper::log('UPDATE', 'Carrera modificada', "Carrera: {$carrera->nombre_carrera}");
        return back()->with('success', 'Carrera actualizada con éxito.');
    }

    public function destroyCarrera($id)
    {
        $carrera = Carrera::findOrFail($id);
        $name = $carrera->nombre_carrera;
        $carrera->delete();
        LoggerHelper::log('DELETE', 'Carrera eliminada', "Carrera: {$name}");
        return back()->with('success', 'Carrera eliminada con éxito.');
    }

    // --- CRUD AULAS ---
    public function indexAulas()
    {
        $aulas = Aula::all();
        return view('admin.aulas', compact('aulas'));
    }

    public function storeAula(Request $request)
    {
        $request->validate([
            'codigo_aula' => 'required|string|max:50|unique:aula,codigo_aula',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:100',
        ]);

        $aula = Aula::create($request->all());
        LoggerHelper::log('CREATE', 'Aula creada', "Aula: {$aula->codigo_aula}");
        return back()->with('success', 'Aula creada con éxito.');
    }

    public function destroyAula($id)
    {
        $aula = Aula::findOrFail($id);
        $codigo = $aula->codigo_aula;
        $aula->delete();
        LoggerHelper::log('DELETE', 'Aula eliminada', "Aula: {$codigo}");
        return back()->with('success', 'Aula eliminada con éxito.');
    }

    // --- CRUD MATERIAS ---
    public function indexMaterias()
    {
        $materias = Materia::all();
        return view('admin.materias', compact('materias'));
    }

    public function storeMateria(Request $request)
    {
        $request->validate([
            'nombre_materia' => 'required|string|max:150',
            'codigo_materia' => 'required|string|max:50|unique:materia,codigo_materia',
            'creditos' => 'required|integer|min:1',
        ]);

        $materia = Materia::create($request->all());
        LoggerHelper::log('CREATE', 'Materia creada', "Materia: {$materia->nombre_materia}");
        return back()->with('success', 'Materia creada con éxito.');
    }

    public function destroyMateria($id)
    {
        $materia = Materia::findOrFail($id);
        $nombre = $materia->nombre_materia;
        $materia->delete();
        LoggerHelper::log('DELETE', 'Materia eliminada', "Materia: {$nombre}");
        return back()->with('success', 'Materia eliminada con éxito.');
    }

    // --- CRUD GRUPOS ---
    public function indexGrupos()
    {
        $grupos = Grupo::with(['aula', 'modalidad', 'turno', 'docente.persona', 'gestion'])->get();
        $aulas = Aula::all();
        $modalidades = Modalidad::all();
        $turnos = Turno::all();
        $docentes = Docente::with('persona')->where('estado', 'Activo')->get();
        $gestiones = Gestion::all();
        return view('admin.grupos', compact('grupos', 'aulas', 'modalidades', 'turnos', 'docentes', 'gestiones'));
    }

    public function storeGrupo(Request $request)
    {
        $request->validate([
            'sigla_grupo' => 'required|string|max:20|unique:grupo,sigla_grupo',
            'capacidad_max' => 'required|integer|min:1',
            'id_aula' => 'required|integer|exists:aula,id_aula',
            'id_modalidad' => 'required|integer|exists:modalidad,id_modalidad',
            'id_turno' => 'required|integer|exists:turno,id_turno',
            'id_docente' => 'required|integer|exists:docente,id_docente',
            'id_gestion' => 'required|integer|exists:gestion,id_gestion',
        ]);

        $grupo = Grupo::create(array_merge($request->all(), [
            'estado' => 'Activo',
            'cant_estudiantes' => 0
        ]));

        LoggerHelper::log('CREATE', 'Grupo creado', "Grupo: {$grupo->sigla_grupo}");
        return back()->with('success', 'Grupo creado con éxito.');
    }

    // --- VALIDACION DE DOCUMENTOS ---
    public function indexDocumentos()
    {
        $documentos = Documento::with(['postulante.persona', 'administrador.persona'])->orderBy('id_documento', 'desc')->get();
        return view('admin.documentos', compact('documentos'));
    }

    // --- GESTIONAR DOCUMENTOS (Requisitos / Tipos de Documentos) ---
    public function indexGestionDocumentos()
    {
        $documentos = Documento::whereNull('id_postulante')->orderBy('id_documento', 'desc')->get();
        return view('admin.gestionar_documentos', compact('documentos'));
    }

    public function storeGestionDocumento(Request $request)
    {
        $request->validate([
            'tipo_documento'   => 'required|string|max:100',
            'nombre'           => 'required|string|max:200',
            'estado'           => 'required|string|in:Pendiente,Validado,Rechazado',
            'observacion'      => 'nullable|string',
            'fecha_registro'   => 'required|date',
            'fecha_validacion' => 'nullable|date',
        ]);

        // Resolver el id_administrador: buscar el registro en la tabla administrador
        // cuyo id_administrador coincida con el id_persona del usuario actual.
        // Si el usuario es SuperAdmin y no tiene fila en administrador, usar el primer admin disponible.
        $idAdmin = null;
        $adminRecord = Administrador::find(Auth::user()->id_persona);
        if ($adminRecord) {
            $idAdmin = $adminRecord->id_administrador;
        } else {
            // Fallback: tomar el primer administrador registrado en el sistema
            $primerAdmin = Administrador::first();
            if ($primerAdmin) {
                $idAdmin = $primerAdmin->id_administrador;
            }
        }

        $doc = Documento::create([
            'tipo_documento'   => $request->tipo_documento,
            'nombre'           => $request->nombre,
            'estado'           => $request->estado,
            'observacion'      => $request->observacion,
            'fecha_registro'   => $request->fecha_registro,
            'fecha_validacion' => $request->fecha_validacion,
            'id_administrador' => $idAdmin,
            'id_postulante'    => null,
        ]);

        LoggerHelper::log('CREATE', 'Documento requisito creado', "Documento: {$doc->nombre} (Tipo: {$doc->tipo_documento})");
        return back()->with('success', 'Documento requisito registrado con éxito.');
    }

    public function updateGestionDocumento(Request $request, $id)
    {
        $request->validate([
            'tipo_documento'   => 'required|string|max:100',
            'nombre'           => 'required|string|max:200',
            'estado'           => 'required|string|in:Pendiente,Validado,Rechazado',
            'observacion'      => 'nullable|string',
            'fecha_registro'   => 'required|date',
            'fecha_validacion' => 'nullable|date',
        ]);

        $doc = Documento::findOrFail($id);
        $doc->update([
            'tipo_documento'   => $request->tipo_documento,
            'nombre'           => $request->nombre,
            'estado'           => $request->estado,
            'observacion'      => $request->observacion,
            'fecha_registro'   => $request->fecha_registro,
            'fecha_validacion' => $request->fecha_validacion,
        ]);

        LoggerHelper::log('UPDATE', 'Documento requisito editado', "Documento ID: {$id} — {$doc->nombre}");
        return back()->with('success', 'Documento actualizado con éxito.');
    }

    public function destroyGestionDocumento($id)
    {
        $doc = Documento::findOrFail($id);
        $nombre = $doc->nombre;
        $doc->delete();
        LoggerHelper::log('DELETE', 'Documento requisito eliminado', "Documento: {$nombre}");
        return back()->with('success', 'Documento eliminado con éxito.');
    }


    public function validateDocument(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|string|in:Validado,Rechazado',
            'observacion' => 'nullable|string',
        ]);

        $documento = Documento::findOrFail($id);
        
        // Find current admin profile
        $adminId = Auth::user()->id_persona;

        $documento->update([
            'estado' => $request->estado,
            'observacion' => $request->observacion,
            'id_administrador' => $adminId,
            'fecha_validacion' => now()->toDateString(),
        ]);

        LoggerHelper::log('PROCESS', 'Validación de documento', "Doc #{$id} - {$request->estado} por Admin ID {$adminId}");
        return back()->with('success', 'El estado del documento ha sido actualizado.');
    }

    // --- VALIDACION DE PAGOS ---
    public function indexPagos()
    {
        $pagos = Pago::with(['comprobante', 'inscripcion.postulante.persona'])->orderBy('id_pago', 'desc')->get();
        return view('admin.pagos', compact('pagos'));
    }

    public function approvePayment(Request $request, $id)
    {
        $request->validate([
            'estado_pago' => 'required|string|in:Pagado,Rechazado',
            'observaciones' => 'nullable|string',
        ]);

        $pago = Pago::findOrFail($id);

        DB::beginTransaction();
        try {
            if ($request->estado_pago === 'Pagado') {
                // Emit Comprobante
                $comprobante = Comprobante::create([
                    'tipo_comprobante' => 'Factura',
                    'numero_comprobante' => 'FAC-' . rand(100000, 999999),
                    'fecha_emision' => now()->toDateString(),
                ]);

                $pago->update([
                    'estado_pago' => 'Pagado',
                    'id_comprobante' => $comprobante->id_comprobante,
                    'observaciones' => $request->observaciones ?? 'Pago aprobado automáticamente.',
                ]);
            } else {
                $pago->update([
                    'estado_pago' => 'Rechazado',
                    'observaciones' => $request->observaciones,
                ]);
            }

            DB::commit();
            LoggerHelper::log('PROCESS', 'Aprobación de Pago', "Pago #{$id} - {$request->estado_pago}");
            return back()->with('success', 'El pago ha sido procesado exitosamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Error al procesar el pago: ' . $e->getMessage()]);
        }
    }

    // --- CRUD SEATS (CUPOCARRERA) ---
    public function indexCupos()
    {
        $cupos = CupoCarrera::with(['carrera', 'gestionRel'])->get();
        $carreras = Carrera::all();
        $gestiones = Gestion::all();
        return view('admin.cupos', compact('cupos', 'carreras', 'gestiones'));
    }

    public function storeCupo(Request $request)
    {
        $request->validate([
            'cantidad_cupos' => 'required|integer|min:1',
            'id_carrera' => 'required|integer|exists:carrera,id_carrera',
            'id_gestion' => 'required|integer|exists:gestion,id_gestion',
        ]);

        $gestion = Gestion::find($request->id_gestion);

        CupoCarrera::create([
            'gestion' => $gestion->anio . '-' . $gestion->periodo,
            'cantidad_cupos' => $request->cantidad_cupos,
            'cupos_ocupados' => 0,
            'cupos_disponibles' => $request->cantidad_cupos,
            'id_carrera' => $request->id_carrera,
            'id_gestion' => $request->id_gestion,
        ]);

        LoggerHelper::log('CREATE', 'Cupo de Carrera asignado', "Carrera ID: {$request->id_carrera}, Cupos: {$request->cantidad_cupos}");
        return back()->with('success', 'Cupos asignados a la carrera con éxito.');
    }

    // --- RUN MERITOCRATIC SEAT ASSIGNMENT (STORED PROCEDURE) ---
    public function runSeatAssignment(Request $request)
    {
        $request->validate([
            'id_carrera' => 'required|integer|exists:carrera,id_carrera',
            'id_gestion' => 'required|integer|exists:gestion,id_gestion',
        ]);

        try {
            // Execute stored procedure in PostgreSQL
            DB::statement("SELECT asignar_cupos_carrera(?, ?)", [
                $request->id_carrera,
                $request->id_gestion
            ]);

            LoggerHelper::log('PROCESS', 'Asignación Meritocrática de Cupos', "Carrera: {$request->id_carrera}, Gestión: {$request->id_gestion}");
            return back()->with('success', 'El proceso meritocrático de asignación de cupos se ha ejecutado correctamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al ejecutar la asignación meritocrática: ' . $e->getMessage()]);
        }
    }

    // --- VIEW AUDIT LOGS (BITACORA) ---
    public function viewBitacora()
    {
        $logs = Bitacora::with(['usuario.persona', 'detalles'])->orderBy('id_bitacora', 'desc')->get();
        return view('admin.bitacora', compact('logs'));
    }
}
