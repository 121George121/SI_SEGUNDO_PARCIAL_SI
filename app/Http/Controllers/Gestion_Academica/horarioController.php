<?php

namespace App\Http\Controllers\Gestion_Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logistica_Recursos_y_Reportes\Grupo;
use App\Models\Logistica_Recursos_y_Reportes\Aula;
use App\Models\Logistica_Recursos_y_Reportes\Modalidad;
use App\Models\Logistica_Recursos_y_Reportes\Turno;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class HorarioController extends Controller
{
   
    public function asignarAula(Request $request, Grupo $grupo)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','Superadministrador'])) abort(403,'No autorizado');

        $request->validate(['Id_aula'=>'required|integer|exists:AULA,Id_aula']);
        $grupo->Id_aula = $request->Id_aula;
        $grupo->save();

        $this->registrarBitacora('Asignar Aula','Grupo '.$grupo->sigla_grupo.' aula asignada: '.$request->Id_aula);

        return redirect()->back()->with('success','Aula asignada correctamente');
    }
  
    public function asignarModalidadTurno(Request $request, Grupo $grupo)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','Superadministrador'])) abort(403,'No autorizado');

        $request->validate([
            'Id_modalidad'=>'required|integer|exists:MODALIDAD,Id_modalidad',
            'Id_turno'=>'required|integer|exists:TURNO,Id_turno'
        ]);

        $grupo->Id_modalidad = $request->Id_modalidad;
        $grupo->Id_turno = $request->Id_turno;
        $grupo->save();

        $this->registrarBitacora('Asignar Modalidad y Turno','Grupo '.$grupo->sigla_grupo.' modalidad: '.$request->Id_modalidad.' turno: '.$request->Id_turno);

        return redirect()->back()->with('success','Modalidad y turno asignados correctamente');
    }

    public function validarCapacidad(Grupo $grupo)
    {
        $disponibles = $grupo->capacidad_max - $grupo->cant_estudiantes;

        $this->registrarBitacora('Validar Capacidad','Grupo '.$grupo->sigla_grupo.' capacidad disponible: '.$disponibles);

        return response()->json([
            'grupo'=>$grupo->sigla_grupo,
            'capacidad_max'=>$grupo->capacidad_max,
            'ocupados'=>$grupo->cant_estudiantes,
            'disponibles'=>$disponibles
        ]);
    }

    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo'=>'Gestión de Grupos',
            'descripcion'=>$descripcion,
            'estado'=>'Exitoso',
            'Id_usuario'=>Auth::user()->Id_usuario
        ]);

        DetalleBitacora::create([
            'Id_bitacora'=>$bitacora->Id_bitacora,
            'direccion_ip'=>request()->ip(),
            'hora_inicio'=>now()->toTimeString(),
            'accion'=>$accion
        ]);
    }
}