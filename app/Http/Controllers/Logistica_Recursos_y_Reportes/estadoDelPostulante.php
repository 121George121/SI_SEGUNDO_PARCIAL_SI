<?php

namespace App\Http\Controllers\Inscripcion_y_Documentacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Documento;
use App\Models\Pago;
use App\Models\Postulante;
use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class EstadoPostulanteController extends Controller
{
    // Consultar estado completo del postulante
    public function consultar()
    {
        $user = Auth::user();
        $postulanteId = $user->id_persona;

        $inscripcion = Inscripcion::where('id_postulante', $postulanteId)->first();
        $documentos = Documento::where('id_postulante', $postulanteId)->get();
        $pagos = Pago::whereHas('inscripcion', function($q) use ($postulanteId){
            $q->where('id_postulante', $postulanteId);
        })->get();
        
        $postulante = Postulante::find($postulanteId);
        $grupo = $postulante ? $postulante->grupos()->first() : null;

        $this->registrarBitacora('Consultar Estado Postulante', 'El postulante '.$postulanteId.' consultó su estado');

        return view('Inscripcion_y_Documentacion.estadoPostulante', compact('inscripcion', 'documentos', 'pagos', 'grupo'));
    }

    // Registrar acción en bitácora
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo'=>'Consulta Estado Postulante',
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