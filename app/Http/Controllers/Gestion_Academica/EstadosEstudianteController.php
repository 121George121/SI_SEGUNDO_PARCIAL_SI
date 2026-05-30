<?php

namespace App\Http\Controllers\Gestion_Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Postulante;
use App\Models\Inscripcion;
use App\Models\Documento;
use App\Models\Pago;
use App\Models\Grupo;
use App\Models\Persona;
use App\Models\Carrera;
use App\Models\Rol;
use App\Models\User;
use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class EstadosEstudianteController extends Controller
{
    public function index(Request $request)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if (!in_array($rol, ['Administrador', 'SuperAdministrador', 'Admin'])) {
            abort(403, 'No autorizado');
        }

    
        $this->ensurePostulantesSeeded();

        // Obtener todos los postulantes 
        $postulantesList = Postulante::with(['persona', 'inscripciones'])->get()->map(function($p) {
            $codigo = $p->inscripciones->first()->codigo_inscripcion ?? 'S/C';
            $nombre = $p->persona ? $p->persona->nombre_completo : 'Sin Nombre';
            return [
                'id_postulante' => $p->id_postulante,
                'codigo' => $codigo,
                'label' => "{$codigo} - {$nombre}"
            ];
        });

        // Buscar postulante seleccionado o por código
        $postulanteSelected = null;
        $inscripcion = null;
        $documentos = collect();
        $pagos = collect();
        $grupo = null;
        $carrera = null;
        $historial = [];

        $searchQuery = $request->input('search_code');

        if ($searchQuery) {

            $inscripcion = Inscripcion::where('codigo_inscripcion', trim($searchQuery))->first();
            if (!$inscripcion) {
                
                $persona = Persona::where('ci', trim($searchQuery))->first();
                if ($persona) {
                    $postulanteSelected = Postulante::find($persona->id_persona);
                    if ($postulanteSelected) {
                        $inscripcion = $postulanteSelected->inscripciones()->first();
                    }
                }
            } else {
                $postulanteSelected = Postulante::find($inscripcion->id_postulante);
            }
        } elseif ($request->filled('id_postulante')) {
            
            $postulanteSelected = Postulante::find($request->id_postulante);
            if ($postulanteSelected) {
                $inscripcion = $postulanteSelected->inscripciones()->first();
            }
        } else {
            
            $inscripcion = Inscripcion::where('codigo_inscripcion', 'POS-2024-000125')->first();
            if ($inscripcion) {
                $postulanteSelected = Postulante::find($inscripcion->id_postulante);
            }
        }

        
        if ($postulanteSelected) {
            $id = $postulanteSelected->id_postulante;

            
            $inscripcion = Inscripcion::where('id_postulante', $id)->first();
            $documentos = Documento::where('id_postulante', $id)->get();
            $pagos = Pago::whereHas('inscripcion', function($q) use ($id) {
                $q->where('id_postulante', $id);
            })->get();
            $grupo = $postulanteSelected->grupos()->first();
            $carrera = $inscripcion ? $inscripcion->carreras()->first() : null;

            if ($inscripcion) {
                $historial[] = [
                    'fecha' => $inscripcion->fecha_inscripcion . ' 08:30:00',
                    'estado' => 'Inscripción Completada',
                    'descripcion' => 'Inscripción realizada correctamente',
                    'observaciones' => 'Formulario de inscripción enviado y código generado: ' . $inscripcion->codigo_inscripcion,
                    'badge' => 'badge-green'
                ];
            }

            foreach ($documentos as $doc) {
                if ($doc->estado === 'Validado') {
                    $historial[] = [
                        'fecha' => ($doc->fecha_validacion ?? $doc->fecha_registro) . ' 10:15:00',
                        'estado' => 'Documentos Validados',
                        'descripcion' => "Documento {$doc->tipo_documento} revisado y aprobado",
                        'observaciones' => $doc->observacion ?? 'Todos los documentos cumplen con los requisitos.',
                        'badge' => 'badge-green'
                    ];
                }
            }

            foreach ($pagos as $pago) {
                if ($pago->estado_pago === 'Pagado') {
                    $historial[] = [
                        'fecha' => $pago->fecha_pago . ' 14:45:00',
                        'estado' => 'Pago Realizado',
                        'descripcion' => 'Pago de inscripción confirmado',
                        'observaciones' => "Metodo: {$pago->metodo_pago}. Monto: Bs. {$pago->monto}. Ref: {$inscripcion->codigo_inscripcion}",
                        'badge' => 'badge-green'
                    ];
                }
            }

            if ($grupo) {
                $historial[] = [
                    'fecha' => $grupo->pivot->fecha_asignacion . ' 16:30:00',
                    'estado' => 'Grupo Asignado',
                    'descripcion' => 'Se asignó al ' . $grupo->sigla_grupo,
                    'observaciones' => 'Asignado automáticamente tras validación de pago.',
                    'badge' => 'badge-blue'
                ];
            }

            
            usort($historial, function($a, $b) {
                return strcmp($b['fecha'], $a['fecha']);
            });

            $this->registrarBitacora('Consultar Estado Postulante', 'Consulta del estado del postulante: ' . ($inscripcion->codigo_inscripcion ?? $postulanteSelected->id_postulante));
        }

        return view('Inscripcion_y_Documentacion.estadosEstudiante', compact(
            'postulantesList',
            'postulanteSelected',
            'inscripcion',
            'documentos',
            'pagos',
            'grupo',
            'carrera',
            'historial'
        ));
    }

    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo' => 'Consulta Estado Postulante',
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

    private function ensurePostulantesSeeded()
    {
        if (Rol::count() == 0) {
            Rol::insert([
                ['id_rol' => 1, 'nombre_rol' => 'SuperAdministrador', 'descripcion' => 'Control total del sistema'],
                ['id_rol' => 2, 'nombre_rol' => 'Admin', 'descripcion' => 'Administrador del sistema'],
                ['id_rol' => 3, 'nombre_rol' => 'Docente', 'descripcion' => 'Docente de la institución'],
                ['id_rol' => 4, 'nombre_rol' => 'Postulante', 'descripcion' => 'Postulante al sistema'],
            ]);
        }

        $codes = ['POS-2024-000125', 'POS-2024-000126', 'POS-2024-000127'];
        foreach ($codes as $code) {
            $insc = Inscripcion::where('codigo_inscripcion', $code)->first();
            if (!$insc) {
                if ($code === 'POS-2024-000125') {
                    $ci = '9000001';
                    $nombre = 'Juan';
                    $apellido = 'Pérez García';
                    $email = 'juan.perez@email.com';
                    $phone = '70000001';
                    $carreraName = 'Ingeniería de Sistemas';
                    $estadoDoc = 'Validado';
                    $estadoPago = 'Pagado';
                    $estadoInsc = 'Asignado';
                    $dateInsc = '2024-05-15';
                    $dateDoc = '2024-05-18';
                    $datePago = '2024-05-20';
                    $dateGrupo = '2024-05-21';
                } elseif ($code === 'POS-2024-000126') {
                    $ci = '9000002';
                    $nombre = 'María';
                    $apellido = 'López Soliz';
                    $email = 'maria.lopez@email.com';
                    $phone = '70000002';
                    $carreraName = 'Telecomunicaciones';
                    $estadoDoc = 'Pendiente';
                    $estadoPago = 'Pagado';
                    $estadoInsc = 'Pendiente';
                    $dateInsc = '2024-05-16';
                    $dateDoc = '2024-05-17';
                    $datePago = '2024-05-19';
                    $dateGrupo = null;
                } else { // POS-2024-000127
                    $ci = '9000003';
                    $nombre = 'Carlos';
                    $apellido = 'Mendoza Rojas';
                    $email = 'carlos.mendoza@email.com';
                    $phone = '70000003';
                    $carreraName = 'Ciencias de la Computación';
                    $estadoDoc = 'Validado';
                    $estadoPago = 'Pendiente';
                    $estadoInsc = 'Pendiente';
                    $dateInsc = '2024-05-10';
                    $dateDoc = '2024-05-12';
                    $datePago = '2024-05-14';
                    $dateGrupo = null;
                }

                $carrera = Carrera::where('nombre_carrera', $carreraName)->first();
                if (!$carrera) {
                    $carrera = Carrera::create([
                        'nombre_carrera' => $carreraName,
                        'descripcion' => 'Carrera de ' . $carreraName,
                        'duracion_anios' => 5
                    ]);
                }

                $pers = Persona::create([
                    'ci' => $ci,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'fecha_nacimiento' => '2005-01-01',
                    'telefono' => $phone,
                    'correo' => $email,
                    'direccion' => 'Barrio Lomas del Este, C/4',
                ]);

                $post = Postulante::create([
                    'id_postulante' => $pers->id_persona,
                    'estado_inscripcion' => $estadoInsc,
                    'fecha_registro' => $dateInsc,
                ]);

                $rolPost = Rol::where('nombre_rol', 'Postulante')->orWhere('id_rol', 4)->first();
                User::create([
                    'nombre_usuario' => $email,
                    'correo' => $email,
                    'contraseña' => bcrypt('123456'),
                    'estado' => 'Activo',
                    'id_rol' => $rolPost->id_rol ?? 4,
                    'id_persona' => $pers->id_persona,
                ]);

                $inscripcion = Inscripcion::create([
                    'codigo_inscripcion' => $code,
                    'estado' => 'Validado',
                    'fecha_inscripcion' => $dateInsc,
                    'id_postulante' => $post->id_postulante,
                ]);

                $inscripcion->carreras()->attach($carrera->id_carrera, [
                    'prioridad' => 'Primera Opción',
                    'estado' => 'Activo'
                ]);

                Documento::create([
                    'tipo_documento' => 'Cédula de Identidad',
                    'nombre' => 'CI_' . $ci . '.pdf',
                    'estado' => $estadoDoc,
                    'fecha_registro' => $dateInsc,
                    'fecha_validacion' => $estadoDoc === 'Validado' ? $dateDoc : null,
                    'id_postulante' => $post->id_postulante,
                ]);

                Documento::create([
                    'tipo_documento' => 'Certificado de Bachiller',
                    'nombre' => 'Bachiller_' . $ci . '.pdf',
                    'estado' => $estadoDoc,
                    'fecha_registro' => $dateInsc,
                    'fecha_validacion' => $estadoDoc === 'Validado' ? $dateDoc : null,
                    'id_postulante' => $post->id_postulante,
                ]);

                Pago::create([
                    'monto' => 350.00,
                    'fecha_pago' => $dateInsc,
                    'metodo_pago' => 'Transferencia Bancaria',
                    'estado_pago' => $estadoPago,
                    'observaciones' => $estadoPago === 'Pagado' ? 'Pago verificado' : 'Pendiente de cobro',
                    'id_inscripcion' => $inscripcion->id_inscripcion,
                ]);

                if ($dateGrupo) {
                    $grupo = Grupo::where('id_carrera', $carrera->id_carrera)->first();
                    if (!$grupo) {
                        $aula = Aula::first();
                        if (!$aula) $aula = Aula::create(['codigo_aula' => 'Aula 101', 'capacidad' => 40, 'ubicacion' => 'Bloque A']);
                        $mod = Modalidad::first();
                        if (!$mod) $mod = Modalidad::create(['nombre_modalidad' => 'Presencial']);
                        $turno = Turno::first();
                        if (!$turno) $turno = Turno::create(['nombre_turno' => 'Mañana']);
                        $gestion = Gestion::first();
                        if (!$gestion) $gestion = Gestion::create(['anio' => '2024', 'periodo' => 'I']);
                        $docente = Docente::first();
                        if (!$docente) {
                            $docPers = Persona::create([
                                'ci' => '99999', 'nombre' => 'Paola', 'apellido' => 'Limon', 'fecha_nacimiento' => '1985-05-04'
                            ]);
                            $docente = Docente::create(['id_docente' => $docPers->id_persona, 'anio_servicio' => 5, 'estado' => 'Activo']);
                        }

                        $grupo = Grupo::create([
                            'sigla_grupo' => 'Grupo A - Ingeniería',
                            'capacidad_max' => 40,
                            'estado' => 'Activo',
                            'cant_estudiantes' => 0,
                            'id_aula' => $aula->id_aula,
                            'id_modalidad' => $mod->id_modalidad,
                            'id_turno' => $turno->id_turno,
                            'id_docente' => $docente->id_docente,
                            'id_gestion' => $gestion->id_gestion,
                            'id_carrera' => $carrera->id_carrera,
                        ]);
                    }

                    $grupo->postulantes()->attach($post->id_postulante, [
                        'estado' => 'Activo',
                        'fecha_asignacion' => $dateGrupo,
                    ]);

                    $grupo->increment('cant_estudiantes');
                }
            }
        }
    }
}
