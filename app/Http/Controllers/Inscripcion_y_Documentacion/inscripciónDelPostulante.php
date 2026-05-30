<?php

namespace App\Http\Controllers\Inscripcion_y_Documentacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Inscripcion_y_Documentacion\Inscripcion;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;

use App\Models\Inscripcion_y_Documentacion\Postulante;

class InscripcionController extends Controller
{
    
    public function index()
    {
      /*  $postulantes = [];
        try {
            $postulantes = Postulante::with('inscripcion')->get()->map(fn($p) => [
                'id'      => 'PST-' . str_pad($p->Id_postulante, 4, '0', STR_PAD_LEFT),
                'nombre'  => trim(($p->nombre ?? '') . ' ' . ($p->apellido ?? '')),
                'ci'      => $p->ci ?? $p->carnet ?? '-',
                'correo'  => $p->correo ?? '-',
                'estado'  => $p->inscripcion->estado ?? 'Pendiente',
            ])->toArray();
        } catch (\Exception $e) {
        
        }*/

        if (empty($postulantes)) {
            $postulantes = [
                ['id' => 'PST-0001', 'nombre' => 'Juan Pérez López', 'ci' => '72912345', 'correo' => 'juan.perez@gmail.com', 'estado' => 'Inscrito'],
                ['id' => 'PST-0002', 'nombre' => 'María Gómez Ramos', 'ci' => '83429182', 'correo' => 'maria.gomez@gmail.com', 'estado' => 'Pendiente'],
                ['id' => 'PST-0003', 'nombre' => 'Carlos Soliz Paz', 'ci' => '90123987', 'correo' => 'carlos.soliz@gmail.com', 'estado' => 'En revisión'],
                ['id' => 'PST-0004', 'nombre' => 'Ana Torrez Ortiz', 'ci' => '10293847', 'correo' => 'ana.torrez@gmail.com', 'estado' => 'Inscrito'],
                ['id' => 'PST-0005', 'nombre' => 'Luis Fernández Roca', 'ci' => '84930219', 'correo' => 'luis.fernandez@gmail.com', 'estado' => 'Pendiente'],
            ];
        }

        return view('Inscripcion_y_Documentacion.inscripcion_Postulante', compact('postulantes'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'Id_postulante' => 'required|integer',
            'datos_basicos' => 'required|array',
        ]);

        
        $existente = Inscripcion::where('Id_postulante', $request->Id_postulante)->first();
        if($existente){
            return back()->withErrors(['inscripcion'=>'Ya existe una inscripción para este postulante']);
        }

      
        $codigo = 'INS-'.Str::upper(Str::random(6));

        $inscripcion = Inscripcion::create([
            'codigo_inscripcion' => $codigo,
            'estado' => 'Activo',
            'Id_postulante' => $request->Id_postulante,
           
        ]);

        $this->registrarBitacora('Registrar Inscripción', 'Inscripción creada: '.$codigo);

        return redirect()->back()->with('success','Inscripción registrada correctamente');
    }

   
    public function update(Request $request, Inscripcion $inscripcion)
    {
        $request->validate([
            'datos_basicos' => 'required|array',
        ]);

        $inscripcion->update([
            'estado' => $request->estado ?? $inscripcion->estado,
            
        ]);

        $this->registrarBitacora('Modificar Inscripción', 'Inscripción modificada: '.$inscripcion->codigo_inscripcion);

        return redirect()->back()->with('success','Inscripción actualizada correctamente');
    }

    // Eliminar inscripción
    public function destroy(Inscripcion $inscripcion)
    {
        $inscripcion->delete();

        $this->registrarBitacora('Eliminar Inscripción', 'Inscripción eliminada: '.$inscripcion->codigo_inscripcion);

        return redirect()->back()->with('success','Inscripción eliminada correctamente');
    }

   
  

  
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo' => 'Gestión de Inscripción',
            'descripcion' => $descripcion,
            'estado' => 'Exitoso',
            'Id_usuario' => auth()->user()->Id_usuario
        ]);

        DetalleBitacora::create([
            'Id_bitacora' => $bitacora->Id_bitacora,
            'direccion_ip' => request()->ip(),
            'hora_inicio' => now()->toTimeString(),
            'accion' => $accion
        ]);
    }
}