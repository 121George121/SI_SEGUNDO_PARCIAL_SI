<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Postulante;
use App\Models\Bitacora;
use App\Models\Inscripcion;
use App\Models\Pago;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // ── KPIs reales de la base de datos ──────────────────────────────
        $stats = [
            'usuarios'       => User::count(),
            'roles'          => Rol::count(),
            'postulantes'    => Postulante::count(),
            'reportes'       => Bitacora::count(),          // total de registros en bitácora
            'estado_sistema' => 'OK',
        ];

        // ── Distribución real de postulantes por estado de inscripción ──
        $estadosInscripcion = Postulante::select('estado_inscripcion', DB::raw('count(*) as total'))
            ->groupBy('estado_inscripcion')
            ->pluck('total', 'estado_inscripcion')
            ->toArray();

        $totalPostulantes = array_sum($estadosInscripcion) ?: 1; // evitar división por 0

        $inscritosPct   = round((($estadosInscripcion['Pendiente']  ?? 0) / $totalPostulantes) * 100);
        $admitidosPct   = round((($estadosInscripcion['Admitido']   ?? 0) / $totalPostulantes) * 100);
        $asignadosPct   = round((($estadosInscripcion['Asignado']   ?? 0) / $totalPostulantes) * 100);
        $noAdmitidosPct = 100 - $inscritosPct - $admitidosPct - $asignadosPct;

        $distribucion = [
            'inscritos'   => $estadosInscripcion['Pendiente'] ?? 0,
            'admitidos'   => $estadosInscripcion['Admitido']  ?? 0,
            'asignados'   => $estadosInscripcion['Asignado']  ?? 0,
            'no_admitidos'=> array_sum(array_filter($estadosInscripcion, fn($k) => !in_array($k, ['Pendiente','Admitido','Asignado']), ARRAY_FILTER_USE_KEY)),
            'inscritos_pct'    => $inscritosPct,
            'admitidos_pct'    => $admitidosPct,
            'asignados_pct'    => $asignadosPct,
            'no_admitidos_pct' => max(0, $noAdmitidosPct),
        ];

        // ── Actividad reciente real desde la bitácora ─────────────────────
        $iconMap = [
            'AUTH'    => ['icono' => 'fa-right-to-bracket', 'bg' => 'bg-blue-50 text-blue-600'],
            'CREATE'  => ['icono' => 'fa-user-plus',        'bg' => 'bg-emerald-50 text-emerald-600'],
            'UPDATE'  => ['icono' => 'fa-pen-to-square',    'bg' => 'bg-amber-50 text-amber-600'],
            'DELETE'  => ['icono' => 'fa-trash',            'bg' => 'bg-rose-50 text-rose-600'],
            'PROCESS' => ['icono' => 'fa-gears',            'bg' => 'bg-purple-50 text-purple-600'],
            'DEFAULT' => ['icono' => 'fa-circle-info',      'bg' => 'bg-cyan-50 text-cyan-600'],
        ];

        $recentLogs = Bitacora::with('usuario.persona')
            ->orderBy('id_bitacora', 'desc')
            ->take(6)
            ->get();

        $actividades = $recentLogs->map(function ($log) use ($iconMap) {
            $tipo   = strtoupper($log->tipo_accion ?? 'DEFAULT');
            $iconCfg = $iconMap[$tipo] ?? $iconMap['DEFAULT'];
            $tiempo = Carbon::parse($log->fecha_hora)->diffForHumans();
            $usuario = optional($log->usuario)->nombre_usuario ?? 'Sistema';
            return [
                'icono'  => $iconCfg['icono'],
                'bg'     => $iconCfg['bg'],
                'titulo' => "[{$tipo}] {$log->descripcion} — {$usuario}",
                'tiempo' => ucfirst($tiempo),
            ];
        })->toArray();

        return view('dashboard.superadmin', compact('stats', 'distribucion', 'actividades'));
    }
}
