<?php

namespace App\Http\Controllers\Inscripcion_y_Documentacion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;

class documentosController extends Controller
{
    
    public function index()
    {
        $documentos = [
            [
                'id' => 1,
                'nombre' => 'DNI / Carnet de Identidad',
                'categoria' => 'Identificación',
                'obligatorio' => 'Sí',
                'descripcion' => 'Documento de identidad vigente.',
                'estado' => 'Activo',
                'observaciones' => 'Debe estar legible y vigente.'
            ],
            [
                'id' => 2,
                'nombre' => 'Certificado de Estudios',
                'categoria' => 'Académico',
                'obligatorio' => 'Sí',
                'descripcion' => 'Certificado oficial de estudios secundarios.',
                'estado' => 'Activo',
                'observaciones' => 'Legalizado por la autoridad educativa.'
            ],
            [
                'id' => 3,
                'nombre' => 'Foto Tamaño Carnet',
                'categoria' => 'Personal',
                'obligatorio' => 'Sí',
                'descripcion' => 'Fotografía reciente tamaño carnet.',
                'estado' => 'Activo',
                'observaciones' => 'Fondo rojo o azul, sin lentes.'
            ],
            [
                'id' => 4,
                'nombre' => 'Comprobante de Pago',
                'categoria' => 'Financiero',
                'obligatorio' => 'No',
                'descripcion' => 'Comprobante de pago por derecho de inscripción.',
                'estado' => 'Activo',
                'observaciones' => 'Subir el voucher escaneado.'
            ],
            [
                'id' => 5,
                'nombre' => 'Declaración Jurada',
                'categoria' => 'Legal',
                'obligatorio' => 'Sí',
                'descripcion' => 'Declaración jurada firmada por el postulante.',
                'estado' => 'Activo',
                'observaciones' => 'Descargar formato de la web.'
            ],
            [
                'id' => 6,
                'nombre' => 'Currículum Vitae',
                'categoria' => 'Personal',
                'obligatorio' => 'No',
                'descripcion' => 'Hoja de vida del postulante.',
                'estado' => 'Activo',
                'observaciones' => 'Máximo 2 páginas.'
            ]
        ];

        return view('Inscripcion_y_Documentacion.documento', compact('documentos'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string',
            'obligatorio' => 'required|string',
        ]);

        $this->registrarBitacora('Registrar Documento', 'Requisito de documento creado (Simulado): ' . $request->nombre);

        return redirect()->back()->with('success', 'Documento registrado correctamente (Simulado)');
    }

   
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string',
            'obligatorio' => 'required|string',
        ]);

        $this->registrarBitacora('Modificar Documento', 'Requisito de documento actualizado (Simulado) ID: ' . $id);

        return redirect()->back()->with('success', 'Documento actualizado correctamente (Simulado)');
    }

    
    public function destroy($id)
    {
        $this->registrarBitacora('Eliminar Documento', 'Requisito de documento eliminado (Simulado) ID: ' . $id);

        return redirect()->back()->with('success', 'Documento eliminado correctamente (Simulado)');
    }

   
    private function registrarBitacora(string $accion, string $descripcion)
    {
        try {
            if (auth()->check()) {
                $bitacora = Bitacora::create([
                    'tipo' => 'Gestión de Documentos',
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
        } catch (\Exception $e) {
           
        }
    }
}
