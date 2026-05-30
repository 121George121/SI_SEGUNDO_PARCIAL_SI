<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\Usuario_Seguridad_y_Auditoria\Postulante;
use App\Models\Gestion_Academica\Grupo;
use App\Models\Gestion_Academica\CupoCarrera;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->isPostulante()) {
            return redirect()->route('postulante.estado');
        }

        // -------------------------------------------------------
        // KPIs REALES desde la base de datos
        // -------------------------------------------------------

        // Total postulantes registrados
        $totalPostulantes = DB::table('postulante')->count();

        // Inscripciones activas (estado = 'Activo')
        $inscripcionesActivas = DB::table('inscripcion')
            ->where('estado', 'Activo')
            ->count();

        // Pagos completados
        $pagosCompletados = DB::table('pago')
            ->where('estado_pago', 'Pagado')
            ->count();

        // Grupos activos
        $gruposActivos = DB::table('grupo')
            ->where('estado', 'Activo')
            ->count();

        // -------------------------------------------------------
        // Documentos pendientes agrupados por tipo
        // -------------------------------------------------------
        $documentosPendientes = DB::table('documento')
            ->where('estado', 'Pendiente')
            ->select('tipo_documento', DB::raw('COUNT(*) as cantidad'))
            ->groupBy('tipo_documento')
            ->orderByDesc('cantidad')
            ->limit(5)
            ->get();

        // -------------------------------------------------------
        // Cupos por carrera (con join a CARRERA)
        // -------------------------------------------------------
        $cuposPorCarrera = DB::table('cupocarrera')
            ->join('carrera', 'cupocarrera.id_carrera', '=', 'carrera.id_carrera')
            ->select(
                'carrera.nombre_carrera',
                'cupocarrera.cantidad_cupos',
                'cupocarrera.cupos_disponibles',
                'cupocarrera.cupos_ocupados'
            )
            ->orderByDesc('cupocarrera.cantidad_cupos')
            ->get();

        // -------------------------------------------------------
        // Donut chart: Inscripciones por estado
        // -------------------------------------------------------
        $inscripcionPorEstado = DB::table('inscripcion')
            ->select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get()
            ->keyBy('estado');

        $activos   = $inscripcionPorEstado->get('Activo')->total   ?? 0;
        $pendiente = $inscripcionPorEstado->get('Pendiente')->total ?? 0;
        $inactivo  = $inscripcionPorEstado->get('Inactivo')->total  ?? 0;
        $cancelado = $inscripcionPorEstado->get('Cancelado')->total ?? 0;
        $totalInscripciones = $activos + $pendiente + $inactivo + $cancelado;

        // Documentos pendientes (total, para donut)
        $docsCount = DB::table('documento')->where('estado', 'Pendiente')->count();

        $donutData = [
            'labels' => ['Inscritos', 'En Proceso', 'Documentos Pendientes', 'Inactivos'],
            'values' => [$activos, $pendiente, $docsCount, $inactivo],
            'colors' => ['#1e40af', '#e31c3d', '#0c2c5a', '#cbd5e1'],
            'activos'    => $activos,
            'pendiente'  => $pendiente,
            'docsCount'  => $docsCount,
            'inactivo'   => $inactivo,
            'total'      => $totalInscripciones,
        ];

        // -------------------------------------------------------
        // Line chart: Inscripciones por mes (últimos 6 meses)
        // -------------------------------------------------------
        $mesesRaw = DB::table('inscripcion')
            ->where('fecha_inscripcion', '>=', now()->subMonths(6)->startOfMonth())
            ->select(
                DB::raw("TO_CHAR(fecha_inscripcion, 'Mon') as mes"),
                DB::raw("TO_CHAR(fecha_inscripcion, 'YYYY-MM') as mes_orden"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('mes', 'mes_orden')
            ->orderBy('mes_orden')
            ->get();

        $lineData = [
            'labels' => $mesesRaw->pluck('mes')->toArray(),
            'values' => $mesesRaw->pluck('total')->toArray(),
        ];

        // If no data yet, show last 6 months with 0
        if (empty($lineData['labels'])) {
            $lineData = $this->emptyLineData();
        }

        return view('dashboard.dashboard', compact(
            'totalPostulantes',
            'inscripcionesActivas',
            'pagosCompletados',
            'gruposActivos',
            'documentosPendientes',
            'cuposPorCarrera',
            'donutData',
            'lineData'
        ));
    }

    // Generates empty line data for 6 months when there are no inscriptions yet
    private function emptyLineData(): array
    {
        $labels = [];
        $values = [];
        for ($i = 5; $i >= 0; $i--) {
            $labels[] = now()->subMonths($i)->locale('es')->isoFormat('MMM');
            $values[] = 0;
        }
        return ['labels' => $labels, 'values' => $values];
    }

    public function usuariosRoles()
    {
        // Roles reales desde la base de datos
        $roles = DB::table('rol')
            ->select('id_rol as id', 'nombre_rol as nombre', 'descripcion')
            ->orderBy('id_rol')
            ->get()
            ->toArray();

        // Usuarios reales con persona y rol
        $usuarios = DB::table('usuario')
            ->join('persona', 'usuario.id_persona', '=', 'persona.id_persona')
            ->join('rol', 'usuario.id_rol', '=', 'rol.id_rol')
            ->select(
                'usuario.id_usuario as id',
                DB::raw("CONCAT(persona.nombre, ' ', persona.apellido) as nombre_completo"),
                'usuario.nombre_usuario as usuario',
                'usuario.correo',
                'persona.ci as dni',
                'persona.telefono',
                'usuario.estado',
                'rol.nombre_rol as rol'
            )
            ->orderBy('usuario.id_usuario')
            ->get()
            ->map(fn($u) => (array)$u)
            ->toArray();

        return view('Usuario_Seguridad_y_Auditoria.usuario_y_Roles', compact('usuarios', 'roles'));
    }
}