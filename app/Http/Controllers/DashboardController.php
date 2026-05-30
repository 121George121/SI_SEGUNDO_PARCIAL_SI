<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->isPostulante()) {
            return redirect()->route('postulante.estado');
        }

        // ------------------------------
        // KPIs simulados
        // ------------------------------
        $totalPostulantes      = 120;
        $inscripcionesActivas  = 85;
        $pagosCompletados      = 75;
        $gruposActivos         = 10;

        // ------------------------------
        // Documentos pendientes simulados
        // ------------------------------
        $documentosPendientes = [
            (object)[ 'tipo_documento' => 'Certificado de Nacimiento', 'cantidad' => 3 ],
            (object)[ 'tipo_documento' => 'Fotocopia CI',           'cantidad' => 5 ],
            (object)[ 'tipo_documento' => 'Foto 4x4',               'cantidad' => 7 ],
        ];

        // ------------------------------
        // Cupos por carrera simulados
        // ------------------------------
        $cuposPorCarrera = [
            (object)[
                'nombre_carrera'    => 'Ingeniería en Sistemas',
                'cantidad_cupos'    => 50,
                'cupos_disponibles' => 20,
                'cupos_ocupados'    => 30
            ],
            (object)[
                'nombre_carrera'    => 'Telecomunicaciones',
                'cantidad_cupos'    => 40,
                'cupos_disponibles' => 15,
                'cupos_ocupados'    => 25
            ],
            (object)[
                'nombre_carrera'    => 'Ciencias de la Computación',
                'cantidad_cupos'    => 30,
                'cupos_disponibles' => 10,
                'cupos_ocupados'    => 20
            ],
        ];

        // ------------------------------
        // Datos para gráfico donut (inscripciones por estado)
        // ------------------------------
        $donutData = [
            'labels' => ['Activo', 'Pendiente', 'Cancelado'],
            'values' => [60, 25, 15],
            'colors' => ['#1e40af', '#dc2626', '#2563eb'],
        ];

        // ------------------------------
        // Datos para gráfico de línea (inscripciones por mes)
        // ------------------------------
        $lineData = [
            'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            'values' => [10, 15, 20, 25, 30, 35],
        ];

        // ------------------------------
        // Retorno a la vista con datos simulados
        // ------------------------------
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
    public function usuariosRoles()
    {
        // Roles simulados
        $roles = [
            ['id'=>1,'nombre'=>'Super Administrador','descripcion'=>'Acceso total al sistema'],
            ['id'=>2,'nombre'=>'Administrador','descripcion'=>'Gestiona usuarios, inscripciones y reportes'],
            ['id'=>3,'nombre'=>'Secretaria','descripcion'=>'Registra postulantes e inscripciones'],
            ['id'=>4,'nombre'=>'Docente','descripcion'=>'Consulta grupos y horarios']
        ];

        // Usuarios simulados
        $usuarios = [
            ['id'=>1,'nombre_completo'=>'Carlos Mendoza','usuario'=>'cmendoza','correo'=>'cmendoza@cup.edu.bo','dni'=>'9856321','telefono'=>'70011223','estado'=>'Activo','rol'=>'Super Administrador'],
            ['id'=>2,'nombre_completo'=>'María López','usuario'=>'mflopez','correo'=>'mflopez@cup.edu.bo','dni'=>'7845123','telefono'=>'72133445','estado'=>'Activo','rol'=>'Secretaria'],
            ['id'=>3,'nombre_completo'=>'José Rojas','usuario'=>'jlrojas','correo'=>'jlrojas@cup.edu.bo','dni'=>'6654789','telefono'=>'69088776','estado'=>'Inactivo','rol'=>'Docente']
        ];

        return view('Usuario_Seguridad_y_Auditoria.usuario_y_Roles', compact('usuarios','roles'));
    }
}