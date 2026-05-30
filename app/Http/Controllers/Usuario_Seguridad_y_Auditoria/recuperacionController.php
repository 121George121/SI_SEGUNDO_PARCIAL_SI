<?php

namespace App\Http\Controllers\Usuario_Seguridad_y_Auditoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use App\Models\User as Usuario;

class recuperacionController extends Controller
{
    private int $codigoExpira = 90; 
    //Mostrar formulario de correo
    public function mostrarRecuperar()
    {
        return view('Usuario_Seguridad_y_Auditoria.recuperacionDeCuenta', ['step' => 1]);
    }

    //Enviar código
    public function enviarCodigo(Request $request)
    {
        $request->validate(['correo' => 'required|email']);

       
        $usuario = Usuario::whereRaw('LOWER(TRIM(correo)) = ?', [strtolower(trim($request->correo))])
                          ->whereRaw('LOWER(TRIM(estado)) = ?', ['activo'])
                          ->first();

        if (!$usuario) {
            return back()->withErrors(['correo' => 'Correo no registrado o usuario inactivo']);
        }

       
        $codigo = rand(100000, 999999);

      
        Cache::put('recuperacion_'.$usuario->id_usuario, $codigo, $this->codigoExpira);

        
        Mail::raw("Tu código de recuperación CUP UAGRM es: $codigo", function ($message) use ($usuario) {
            $message->to($usuario->correo)
                    ->subject('Código de recuperación CUP UAGRM');
        });

        return view('Usuario_Seguridad_y_Auditoria.recuperacionDeCuenta', [
            'step' => 2,
            'usuario_id' => $usuario->id_usuario,
            'success' => "Se envió un código a tu correo. Tienes {$this->codigoExpira} segundos para usarlo."
        ]);
    }

    // Validar código
    public function validarCodigo(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|integer',
            'codigo' => 'required|digits:6'
        ]);

        $usuario = Usuario::find($request->usuario_id);
        if (!$usuario) {
            return back()->withErrors(['codigo' => 'Usuario no encontrado']);
        }

        $codigoGuardado = Cache::get('recuperacion_'.$usuario->id_usuario);

        if (!$codigoGuardado) {
            return back()->withErrors(['codigo' => 'El código expiró o es inválido']);
        }

        if ($codigoGuardado != $request->codigo) {
            return back()->withErrors(['codigo' => 'Código incorrecto']);
        }

        // Código correcto: mostrar formulario para nueva contraseña
        return view('Usuario_Seguridad_y_Auditoria.recuperacionDeCuenta', [
            'step' => 3,
            'usuario_id' => $usuario->id_usuario
        ]);
    }

    // Cambiar contraseña
    public function cambiarContrasena(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|integer',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/'
            ]
        ]);

        $usuario = Usuario::find($request->usuario_id);
        if (!$usuario) {
            return back()->withErrors(['password' => 'Usuario no encontrado']);
        }

        // Guardar nueva contraseña en DB
        $usuario->contraseña = Hash::make($request->password);
        $usuario->save();

     
        Cache::forget('recuperacion_'.$usuario->id_usuario);

        return redirect()->route('login')->with('success', 'Contraseña actualizada correctamente');
    }
}