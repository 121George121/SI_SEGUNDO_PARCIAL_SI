<?php

namespace App\Http\Controllers\Logistica_Recursos_y_Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aula;
use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class AulasController extends Controller
{
    // Listar aulas
    public function index(Request $request)
    {
        $query = Aula::query();

        if ($request->filled('buscar')) {
            $buscar = '%' . $request->buscar . '%';
            $query->where(function($q) use ($buscar) {
                $q->where('codigo_aula', 'ILIKE', $buscar)
                  ->orWhere('ubicacion', 'ILIKE', $buscar);
            });
        }

        $aulas = $query->orderBy('id_aula', 'asc')->paginate(10);

        return view('Logistica_Recursos_y_Reportes.aula', compact('aulas'));
    }

    // Registrar nueva aula
    public function registrar(Request $request)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'facultad' => 'required|string',
            'edificio' => 'required|string',
            'piso' => 'required|string',
            'codigo_aula' => 'required|string|unique:aula,codigo_aula',
            'capacidad' => 'required|integer|min:1',
            'estado' => 'required|string',
            'descripcion' => 'nullable|string'
        ]);

        $aula = new Aula();
        $aula->codigo_aula = $request->codigo_aula;
        $aula->capacidad = $request->capacidad;
        $aula->facultad = $request->facultad;
        $aula->edificio = $request->edificio;
        $aula->piso = $request->piso;
        $aula->estado = $request->estado;
        $aula->descripcion = $request->descripcion;
        $aula->save();

        $this->registrarBitacora('Registrar Aula','Aula registrada: '.$aula->codigo_aula);

        return redirect()->back()->with('success','Aula registrada correctamente');
    }

    // Editar aula
    public function editar(Request $request, Aula $aula)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'facultad' => 'required|string',
            'edificio' => 'required|string',
            'piso' => 'required|string',
            'codigo_aula' => 'required|string|unique:aula,codigo_aula,'.$aula->id_aula.',id_aula',
            'capacidad' => 'required|integer|min:1',
            'estado' => 'required|string',
            'descripcion' => 'nullable|string'
        ]);

        $aula->codigo_aula = $request->codigo_aula;
        $aula->capacidad = $request->capacidad;
        $aula->facultad = $request->facultad;
        $aula->edificio = $request->edificio;
        $aula->piso = $request->piso;
        $aula->estado = $request->estado;
        $aula->descripcion = $request->descripcion;
        $aula->save();

        $this->registrarBitacora('Editar Aula','Aula editada: '.$aula->codigo_aula);

        return redirect()->back()->with('success','Aula actualizada correctamente');
    }

    // Eliminar aula
    public function destroy(Aula $aula)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $codigo = $aula->codigo_aula;
        $aula->delete();

        $this->registrarBitacora('Eliminar Aula','Aula eliminada: '.$codigo);

        return redirect()->back()->with('success','Aula eliminada correctamente');
    }

    // Registrar acciones en bitácora
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo'=>'Gestión de Aulas',
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