<?php

namespace App\Http\Controllers\Usuario_Seguridad_y_Auditoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario_Seguridad_y_Auditoria\User;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;

class autenticacionController extends Controller
{
    private int $maxIntentos = 3;
    private int $tiempoBloqueo = 10; // minutos

    public function mostrarLogin()
    {
        return view('Usuario_Seguridad_y_Auditoria.login');
    }

    public function iniciarSesion(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar usuario por correo en la tabla usuario
        $usuario = User::where('correo', $request->correo)
            ->where('estado', 'Activo')
            ->first();

        if (!$usuario) {
            return back()->withErrors(['login' => 'Usuario o contraseña incorrecta'])->withInput();
        }

        // Verificar bloqueo temporal
        if (!is_null($usuario->bloqueado_hasta) && now()->lessThan($usuario->bloqueado_hasta)) {
            $minutos = now()->diffInMinutes($usuario->bloqueado_hasta);
            return back()->withErrors(['login' => "Usuario bloqueado temporalmente. Intente en $minutos minutos"])->withInput();
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $usuario->getAuthPassword())) {
            $usuario->intentos_fallidos = ($usuario->intentos_fallidos ?? 0) + 1;

            if ($usuario->intentos_fallidos >= $this->maxIntentos) {
                $usuario->bloqueado_hasta = now()->addMinutes($this->tiempoBloqueo);
            }

            $usuario->save();

            return back()->withErrors(['login' => 'Usuario o contraseña incorrecta'])->withInput();
        }

        // Reiniciar intentos y actualizar último login
        $usuario->intentos_fallidos = 0;
        $usuario->bloqueado_hasta = null;
        $usuario->ultimo_login = now();
        $usuario->save();

        // Registrar bitácora
        $bitacora = Bitacora::create([
            'tipo' => 'Autenticación',
            'descripcion' => 'Inicio de sesión exitoso',
            'estado' => 'Exitoso',
            'id_usuario' => $usuario->id_usuario
        ]);

        DetalleBitacora::create([
            'id_bitacora' => $bitacora->id_bitacora,
            'direccion_ip' => $request->ip(),
            'hora_inicio' => now()->toTimeString(),
            'accion' => 'Login',
        ]);

        Auth::login($usuario);

        return redirect()->route('dashboard');
    }

    public function cerrarSesion(Request $request)
    {
        $usuario = Auth::user();

        if ($usuario) {
            $bitacora = Bitacora::create([
                'tipo' => 'Autenticación',
                'descripcion' => 'Cierre de sesión',
                'estado' => 'Exitoso',
                'id_usuario' => $usuario->id_usuario
            ]);

            DetalleBitacora::create([
                'id_bitacora' => $bitacora->id_bitacora,
                'direccion_ip' => $request->ip(),
                'hora_inicio' => now()->toTimeString(),
                'accion' => 'Logout',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}