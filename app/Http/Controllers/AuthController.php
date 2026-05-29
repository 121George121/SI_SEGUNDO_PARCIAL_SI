<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Rol;
use App\Models\Postulante;
use App\Models\Inscripcion;
use App\Models\InscripcionCarrera;
use App\Models\Comprobante;
use App\Models\Pago;
use App\Models\Carrera;
use App\Helpers\LoggerHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Show login form
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectUser(Auth::user());
        }
        return view('auth.login');
    }

    // Handle login attempt
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required|string',
        ]);

        $correo = strtolower($request->input('correo'));
        $throttleKey = $correo . '|' . $request->ip();

        // 1. Check if blocked
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'correo' => "Acceso bloqueado por intentos fallidos. Intente de nuevo en {$seconds} segundos."
            ])->withInput($request->except('contraseña'));
        }

        // 2. Query user
        $user = User::where('correo', $correo)->first();

        // 3. Verify user and password
        if ($user && $user->estado === 'Activo' && Hash::check($request->input('contraseña'), $user->contraseña)) {
            // Success
            Auth::login($user);
            RateLimiter::clear($throttleKey);

            // Log action in bitacora
            LoggerHelper::log('AUTH', 'Inicio de sesión exitoso', "Usuario: {$user->nombre_usuario}");

            return $this->redirectUser($user);
        }

        // 4. Failed login
        RateLimiter::hit($throttleKey, 60); // Block for 1 minute (60 seconds) on 3rd failure
        $attemptsLeft = 3 - RateLimiter::attempts($throttleKey);

        if ($attemptsLeft <= 0) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'correo' => "Demasiados intentos fallidos. Su cuenta ha sido bloqueada por 1 minuto ({$seconds} segundos)."
            ])->withInput($request->except('contraseña'));
        }

        return back()->withErrors([
            'correo' => "Credenciales incorrectas. Intentos restantes antes del bloqueo: {$attemptsLeft}"
        ])->withInput($request->except('contraseña'));
    }

    // Handle logout
    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user();
            LoggerHelper::log('AUTH', 'Cierre de sesión', "Usuario: {$user->nombre_usuario}");
            Auth::logout();
        }
        return redirect()->route('login');
    }

    // Show Register Form for Postulantes
    public function showRegister()
    {
        $carreras = Carrera::all();
        return view('auth.register', compact('carreras'));
    }

    // Handle Register
    public function register(Request $request)
    {
        $request->validate([
            'ci' => 'required|string|unique:persona,ci',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string',
            'correo' => 'required|email|unique:usuario,correo|unique:persona,correo',
            'nombre_usuario' => 'required|string|max:50|unique:usuario,nombre_usuario',
            'contraseña' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'carrera_id' => 'required|integer|exists:carrera,id_carrera'
        ], [
            'contraseña.regex' => 'La contraseña debe contener mayúsculas, minúsculas, números y caracteres especiales.',
        ]);

        \DB::beginTransaction();
        try {
            // 1. Create Persona
            $persona = Persona::create([
                'ci' => $request->input('ci'),
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                'telefono' => $request->input('telefono'),
                'direccion' => $request->input('direccion'),
                'correo' => strtolower($request->input('correo')),
            ]);

            // 2. Find Postulante Rol
            $rol = Rol::where('nombre_rol', 'Postulante')->first();
            if (!$rol) {
                $rol = Rol::create(['nombre_rol' => 'Postulante', 'descripcion' => 'Rol de Postulantes']);
            }

            // 3. Create Usuario
            $user = User::create([
                'nombre_usuario' => $request->input('nombre_usuario'),
                'correo' => strtolower($request->input('correo')),
                'contraseña' => Hash::make($request->input('contraseña')),
                'estado' => 'Activo',
                'fecha_creacion' => now()->toDateString(),
                'id_rol' => $rol->id_rol,
                'id_persona' => $persona->id_persona,
            ]);

            // 4. Create Postulante
            $postulante = Postulante::create([
                'id_postulante' => $persona->id_persona,
                'estado_inscripcion' => 'Pendiente',
                'fecha_registro' => now()->toDateString(),
                'id_asignacion' => null,
            ]);

            // 5. Create Inscripcion
            $codigoInsc = 'INS-' . strtoupper(uniqid());
            $inscripcion = Inscripcion::create([
                'codigo_inscripcion' => $codigoInsc,
                'estado' => 'Activo',
                'fecha_inscripcion' => now()->toDateString(),
                'id_postulante' => $postulante->id_postulante,
            ]);

            // 6. Associate Career
            InscripcionCarrera::create([
                'id_inscripcion' => $inscripcion->id_inscripcion,
                'id_carrera' => $request->input('carrera_id'),
                'prioridad' => '1',
                'estado' => 'Activo',
            ]);

            // 7. Initialize a pending Payment record for enrollment
            Pago::create([
                'monto' => 350.00, // standard pre-university exam fee in Bs.
                'fecha_pago' => now()->toDateString(),
                'metodo_pago' => 'Transferencia Bancaria',
                'estado_pago' => 'Pendiente',
                'observaciones' => 'Pago inicial pendiente de registro de comprobante',
                'id_comprobante' => null,
                'id_inscripcion' => $inscripcion->id_inscripcion,
            ]);

            \DB::commit();

            // Log action in bitacora
            LoggerHelper::log('AUTH', 'Registro de nuevo postulante', "Postulante: {$persona->nombre_completo}", $user->id_usuario);

            Auth::login($user);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \DB::rollback();
            return back()->withErrors(['error' => 'Error al registrar el postulante: ' . $e->getMessage()])->withInput();
        }
    }

    // Redirect user based on their role
    protected function redirectUser($user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->rol && $user->rol->nombre_rol === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDocente()) {
            return redirect()->route('docente.dashboard');
        } elseif ($user->isPostulante()) {
            return redirect()->route('postulante.dashboard');
        }
        return redirect('/');
    }

    // Password Recovery Methods
    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function sendRecoveryCode(Request $request)
    {
        $request->validate(['correo' => 'required|email|exists:usuario,correo']);
        
        $email = strtolower($request->input('correo'));
        $code = mt_rand(100000, 999999);

        // Guardar código en caché por 10 minutos
        Cache::put('recovery_' . $email, $code, now()->addMinutes(10));

        // Registrar en log del servidor (NO en la interfaz)
        Log::info("[RecoveryCode] Código generado para {$email}");

        // Enviar correo real por Gmail SMTP
        try {
            Mail::send('emails.recovery', ['code' => $code, 'email' => $email], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Código de Recuperación de Contraseña - CUP UAGRM');
            });
        } catch (\Exception $e) {
            Log::error("[RecoveryCode] Error de envío a {$email}: " . $e->getMessage());
            // Si falla el envío, eliminar el código del caché y notificar al usuario
            Cache::forget('recovery_' . $email);
            return back()->withErrors([
                'correo' => 'No se pudo enviar el correo de recuperación. Verifique que la dirección sea correcta e intente de nuevo.'
            ])->withInput();
        }

        // Guardar el email en sesión para usarlo en la pantalla de verificación
        session(['recovery_email' => $email]);

        return redirect()->route('password.verify')
            ->with('status', "Código de 6 dígitos enviado al correo {$email}. Revisa tu bandeja de entrada (y carpeta de spam).");
    }

    public function showVerify()
    {
        $email = session('recovery_email') ?? old('correo');
        return view('auth.verify', compact('email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|exists:usuario,correo',
            'code' => 'required|numeric',
            'contraseña' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'contraseña.regex' => 'La contraseña debe contener mayúsculas, minúsculas, números y caracteres especiales.',
        ]);

        $email = strtolower($request->input('correo'));
        $cachedCode = Cache::get('recovery_' . $email);

        if (!$cachedCode || $cachedCode != $request->input('code')) {
            return back()->withErrors(['code' => 'El código de verificación es inválido o ha expirado.'])->withInput();
        }

        // Reset password
        $user = User::where('correo', $email)->first();
        if ($user) {
            $user->contraseña = Hash::make($request->input('contraseña'));
            $user->save();

            // Clear cache
            Cache::forget('recovery_' . $email);

            LoggerHelper::log('AUTH', 'Recuperación de contraseña', "Usuario: {$user->nombre_usuario}", $user->id_usuario);

            return redirect()->route('login')->with('status', 'Su contraseña ha sido reestablecida exitosamente. Ahora puede iniciar sesión.');
        }

        return back()->withErrors(['correo' => 'Error al reestablecer la contraseña.'])->withInput();
    }
}
