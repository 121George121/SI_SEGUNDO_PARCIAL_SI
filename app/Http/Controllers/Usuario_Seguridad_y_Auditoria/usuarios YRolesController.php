<?php

namespace App\Http\Controllers\Usuario_Seguridad_y_Auditoria;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario_Seguridad_y_Auditoria\Usuario;
use App\Models\Usuario_Seguridad_y_Auditoria\Rol;
use App\Models\Usuario_Seguridad_y_Auditoria\Bitacora;
use App\Models\Usuario_Seguridad_y_Auditoria\DetalleBitacora;

class UsuarioYRolesController extends Controller
{
    // Crear nuevo usuario
    public function create()
    {
        $roles = Rol::all();
        return view('Usuario_Seguridad_y_Auditoria.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre_usuario' => 'required|string|unique:USUARIO,nombre_usuario',
            'correo' => 'required|email|unique:USUARIO,correo',
            'password' => 'required|string|min:6|confirmed',
            'Id_rol' => 'required|integer|exists:ROL,Id_rol',
        ]);

        $rolActual = auth()->user()->rol->nombre_rol ?? '';
        if (!in_array($rolActual, ['Administrador','Superadministrador'])) {
            abort(403,'No autorizado');
        }

        $usuario = Usuario::create([
            'nombre_usuario' => $request->nombre_usuario,
            'correo' => $request->correo,
            'contraseña' => Hash::make($request->password),
            'estado' => 'Activo',
            'Id_rol' => $request->Id_rol,
            'Id_persona' => $request->Id_persona ?? null
        ]);

        $this->registrarBitacora('Crear Usuario','Usuario creado: '.$usuario->nombre_usuario);

        return redirect()->back()->with('success','Usuario creado correctamente');
    }

    // Editar usuario existente
    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('Usuario_Seguridad_y_Auditoria.usuarios.edit', compact('usuario','roles'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $this->validate($request, [
            'nombre_usuario' => 'required|string|unique:USUARIO,nombre_usuario,'.$usuario->Id_usuario.',Id_usuario',
            'correo' => 'required|email|unique:USUARIO,correo,'.$usuario->Id_usuario.',Id_usuario',
            'password' => 'nullable|string|min:6|confirmed',
            'Id_rol' => 'required|integer|exists:ROL,Id_rol',
        ]);

        $rolActual = auth()->user()->rol->nombre_rol ?? '';
        if (!in_array($rolActual, ['Administrador','Superadministrador'])) {
            abort(403,'No autorizado');
        }

        $usuario->nombre_usuario = $request->nombre_usuario;
        $usuario->correo = $request->correo;
        if ($request->password) {
            $usuario->contraseña = Hash::make($request->password);
        }
        $usuario->Id_rol = $request->Id_rol;
        $usuario->save();

        $this->registrarBitacora('Editar Usuario','Usuario editado: '.$usuario->nombre_usuario);

        return redirect()->back()->with('success','Usuario actualizado correctamente');
    }

    // Eliminar usuario
    public function destroy(Usuario $usuario)
    {
        $rolActual = auth()->user()->rol->nombre_rol ?? '';
        if (!in_array($rolActual, ['Administrador','Superadministrador'])) {
            abort(403,'No autorizado');
        }

        $usuario->delete();

        $this->registrarBitacora('Eliminar Usuario','Usuario eliminado: '.$usuario->nombre_usuario);

        return redirect()->back()->with('success','Usuario eliminado correctamente');
    }

    // Asignar rol y privilegios a un usuario
    public function asignarRol(Request $request, Usuario $usuario)
    {
        $this->validate($request, [
            'Id_rol' => 'required|integer|exists:ROL,Id_rol',
        ]);

        $rolActual = auth()->user()->rol->nombre_rol ?? '';
        if (!in_array($rolActual, ['Administrador','Superadministrador'])) {
            abort(403,'No autorizado');
        }

        $usuario->Id_rol = $request->Id_rol;
        $usuario->save();

        $this->registrarBitacora('Asignar Rol','Rol asignado a usuario: '.$usuario->nombre_usuario);

        return redirect()->back()->with('success','Rol asignado correctamente');
    }

    // Registrar acciones en Bitacora
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo' => 'Gestión de Usuarios',
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