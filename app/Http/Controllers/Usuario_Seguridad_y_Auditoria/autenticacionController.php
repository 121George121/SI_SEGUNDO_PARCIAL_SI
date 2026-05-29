<?php

namespace App\Http\Controllers\Usuario_Seguridad_y_Auditoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario_Seguridad_y_Auditoria\Usuario;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;

class autenticacionController extends Controller
{
    private int $maxIntentos = 3;
    private int $tiempoBloqueo = 10; // minutos

    // Mostrar formulario de login
    public function mostrarLogin()
    {
        return view('auth.login');
    }

    // Iniciar sesión
    public function iniciarSesion(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('nombre_usuario', $request->usuario)
            ->orWhere('correo', $request->usuario)
            ->first();

        if (!$usuario) {
            return back()->withErrors(['login' => 'Credenciales incorrectas'])->withInput();
        }

        if ($usuario->estado !== 'Activo') {
            return back()->withErrors(['login' => 'Usuario inactivo'])->withInput();
        }

        if ($usuario->bloqueado_hasta && now()->lessThan($usuario->bloqueado_hasta)) {
            $minutos = now()->diffInMinutes($usuario->bloqueado_hasta);
            return back()->withErrors(['login' => "Usuario bloqueado temporalmente. Intente en $minutos minutos"])->withInput();
        }

        if (!Hash::check($request->password, $usuario->contraseña)) {
            $usuario->intentos_fallidos++;
            if ($usuario->intentos_fallidos >= $this->maxIntentos) {
                $usuario->bloqueado_hasta = now()->addMinutes($this->tiempoBloqueo);
            }
            $usuario->save();
            return back()->withErrors(['login' => 'Credenciales incorrectas'])->withInput();
        }

        $usuario->intentos_fallidos = 0;
        $usuario->bloqueado_hasta = null;
        $usuario->ultimo_login = now();
        $usuario->save();

        // Registrar bitácora
        $bitacora = Bitacora::create([
            'tipo' => 'Autenticación',
            'descripcion' => 'Inicio de sesión exitoso',
            'estado' => 'Exitoso',
            'Id_usuario' => $usuario->Id_usuario
        ]);

        DetalleBitacora::create([
            'Id_bitacora' => $bitacora->Id_bitacora,
            'direccion_ip' => $request->ip(),
            'hora_inicio' => now()->toTimeString(),
            'accion' => 'Login',
        ]);

        Auth::login($usuario);

        // Redirigir a dashboard único
        return redirect()->route('dashboard');
    }

    // Cerrar sesión
    public function cerrarSesion(Request $request)
    {
        $usuario = Auth::user();

        if ($usuario) {
            $bitacora = Bitacora::create([
                'tipo' => 'Autenticación',
                'descripcion' => 'Cierre de sesión',
                'estado' => 'Exitoso',
                'Id_usuario' => $usuario->Id_usuario
            ]);

            DetalleBitacora::create([
                'Id_bitacora' => $bitacora->Id_bitacora,
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