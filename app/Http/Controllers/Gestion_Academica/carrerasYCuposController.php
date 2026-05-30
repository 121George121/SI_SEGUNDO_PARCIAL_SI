<?php

namespace App\Http\Controllers\Gestion_Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gestion_Academica\Carrera;
use App\Models\Gestion_Academica\CupoCarrera;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class CarrerasCuposController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all();
        $gestiones = \App\Models\Gestion_Academica\Gestion::all();
        return view('Gestion_Academica.carreraYCupos', compact('carreras', 'gestiones'));
    }

    // Registrar una nueva carrera
    public function registrarCarrera(Request $request)
    {
        $request->validate([
            'nombre_carrera'=>'required|string|unique:CARRERA,nombre_carrera',
            'descripcion'=>'nullable|string',
            'duracion_anios'=>'required|integer|min:1',
        ]);

        $rol = Auth::user()->rol->nombre_rol ?? '';
        if($rol !== 'Administrador') abort(403,'No autorizado');

        $carrera = Carrera::create([
            'nombre_carrera'=>$request->nombre_carrera,
            'descripcion'=>$request->descripcion,
            'duracion_anios'=>$request->duracion_anios,
        ]);

        $this->registrarBitacora('Registrar Carrera','Carrera registrada: '.$carrera->nombre_carrera);

        return redirect()->back()->with('success','Carrera registrada correctamente');
    }

    // Actualizar cupos de una carrera
    public function actualizarCupos(Request $request, Carrera $carrera)
    {
        $request->validate([
            'cantidad_cupos'=>'required|integer|min:1'
        ]);

        $rol = Auth::user()->rol->nombre_rol ?? '';
        if($rol !== 'Administrador') abort(403,'No autorizado');

        $cupo = CupoCarrera::updateOrCreate(
            ['Id_carrera'=>$carrera->Id_carrera, 'Id_gestion'=>$request->Id_gestion],
            [
                'cantidad_cupos'=>$request->cantidad_cupos,
                'cupos_ocupados'=>0,
                'cupos_disponibles'=>$request->cantidad_cupos,
                'gestion'=>$request->gestion ?? '2026',
            ]
        );

        $this->registrarBitacora('Actualizar Cupos','Carrera: '.$carrera->nombre_carrera.' Cupos: '.$request->cantidad_cupos);

        return redirect()->back()->with('success','Cupos actualizados correctamente');
    }

    // Consultar cupos disponibles
    public function consultarCupos(Carrera $carrera)
    {
        $cupo = CupoCarrera::where('Id_carrera',$carrera->Id_carrera)->get();
        return view('Gestion_Academica.cupos.index', compact('carrera','cupo'));
    }

    // Deshabilitar carrera
    public function deshabilitarCarrera(Carrera $carrera)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if($rol !== 'Administrador') abort(403,'No autorizado');

        $carrera->estado = 'Inactivo';
        $carrera->save();

        $this->registrarBitacora('Deshabilitar Carrera','Carrera deshabilitada: '.$carrera->nombre_carrera);

        return redirect()->back()->with('success','Carrera deshabilitada correctamente');
    }

    // Registrar acciones en bitácora
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo'=>'Gestión de Carreras y Cupos',
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