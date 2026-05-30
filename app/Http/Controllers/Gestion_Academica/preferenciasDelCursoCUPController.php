<?php

namespace App\Http\Controllers\Gestion_Academica;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscripcion_y_Documentacion\PreferenciasDelCursoCUP;
use App\Models\Modalidad;
use App\Models\Turno;
use App\Models\Gestion;
use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class preferenciasDelCursoCUPController extends Controller
{
    
    public function index(Request $request)
    {
        $query = PreferenciasDelCursoCUP::query();

        if ($request->filled('buscar')) {
            $buscar = '%' . $request->buscar . '%';
            $query->where(function($q) use ($buscar) {
                $q->where('modalidad', 'ILIKE', $buscar)
                  ->orWhere('turno', 'ILIKE', $buscar)
                  ->orWhere('periodo_academico', 'ILIKE', $buscar)
                  ->orWhere('descripcion', 'ILIKE', $buscar);
            });
        }

        $preferencias = $query->orderBy('id_preferencia', 'asc')->paginate(10);
        $modalidades = Modalidad::all();
        $turnos = Turno::all();
        $gestiones = Gestion::all();

        return view('Gestion_Academica.preferenciasDelCursoCUP', compact('preferencias', 'modalidades', 'turnos', 'gestiones'));
    }

    
    public function guardarConfig(Request $request)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'modalidad' => 'required|string',
            'turno' => 'required|string',
            'periodo_academico' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string',
            'descripcion' => 'nullable|string'
        ]);

        $preferencia = PreferenciasDelCursoCUP::create([
            'modalidad' => $request->modalidad,
            'turno' => $request->turno,
            'periodo_academico' => $request->periodo_academico,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion
        ]);

        $this->registrarBitacora('Registrar Configuración Preferencia', 'Preferencia configurada para Modalidad: '.$preferencia->modalidad.', Turno: '.$preferencia->turno.', Periodo: '.$preferencia->periodo_academico);

        return redirect()->back()->with('success','Configuración de preferencia guardada correctamente');
    }

  
    public function editarConfig(Request $request, $id)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'modalidad' => 'required|string',
            'turno' => 'required|string',
            'periodo_academico' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'estado' => 'required|string',
            'descripcion' => 'nullable|string'
        ]);

        $preferencia = PreferenciasDelCursoCUP::findOrFail($id);
        $preferencia->update([
            'modalidad' => $request->modalidad,
            'turno' => $request->turno,
            'periodo_academico' => $request->periodo_academico,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => $request->estado,
            'descripcion' => $request->descripcion
        ]);

        $this->registrarBitacora('Editar Configuración Preferencia', 'Preferencia editada: ID '.$preferencia->id_preferencia);

        return redirect()->back()->with('success','Configuración de preferencia actualizada correctamente');
    }

 
    public function eliminarConfig($id)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $preferencia = PreferenciasDelCursoCUP::findOrFail($id);
        $id_pref = $preferencia->id_preferencia;
        $preferencia->delete();

        $this->registrarBitacora('Eliminar Configuración Preferencia', 'Preferencia eliminada: ID '.$id_pref);

        return redirect()->back()->with('success','Configuración de preferencia eliminada correctamente');
    }

 
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo' => 'Gestión de Preferencias del Curso',
            'descripcion' => $descripcion,
            'estado' => 'Exitoso',
            'Id_usuario' => Auth::user()->Id_usuario
        ]);

        DetalleBitacora::create([
            'Id_bitacora' => $bitacora->Id_bitacora,
            'direccion_ip' => request()->ip(),
            'hora_inicio' => now()->toTimeString(),
            'accion' => $accion
        ]);
    }
}