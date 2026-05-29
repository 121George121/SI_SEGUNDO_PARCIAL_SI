<?php

namespace App\Http\Controllers\Inscripcion_y_Documentacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Inscripcion_y_Documentacion\Inscripcion;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;

class InscripcionController extends Controller
{
    // Registrar nueva inscripción
    public function store(Request $request)
    {
        $request->validate([
            'Id_postulante' => 'required|integer',
            'datos_basicos' => 'required|array',
        ]);

        // Validar duplicados
        $existente = Inscripcion::where('Id_postulante', $request->Id_postulante)->first();
        if($existente){
            return back()->withErrors(['inscripcion'=>'Ya existe una inscripción para este postulante']);
        }

        // Generar código único
        $codigo = 'INS-'.Str::upper(Str::random(6));

        $inscripcion = Inscripcion::create([
            'codigo_inscripcion' => $codigo,
            'estado' => 'Activo',
            'Id_postulante' => $request->Id_postulante,
            // almacenar datos adicionales según tu tabla
        ]);

        $this->registrarBitacora('Registrar Inscripción', 'Inscripción creada: '.$codigo);

        return redirect()->back()->with('success','Inscripción registrada correctamente');
    }

    // Modificar inscripción
    public function update(Request $request, Inscripcion $inscripcion)
    {
        $request->validate([
            'datos_basicos' => 'required|array',
        ]);

        $inscripcion->update([
            'estado' => $request->estado ?? $inscripcion->estado,
            // actualizar otros campos según tu modelo
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

    // Validar datos básicos (opcional, según tabla)
    private function validarDatos(array $datos)
    {
        // Aquí podrías implementar validaciones extra
    }

    // Registrar en bitácora
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