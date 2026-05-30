<?php

namespace App\Http\Controllers\Usuario_Seguridad_y_Auditoria;

use App\Http\Controllers\Controller;
use App\Models\Usuario_Seguridad_y_Auditoria\Rol;
use App\Models\Usuario_Seguridad_y_Auditoria\User;
use App\Models\Usuario_Seguridad_y_Auditoria\Persona;
use Illuminate\Http\Request;

class usuariosYRolesController extends Controller
{
    // Mostrar vista principal de Usuarios y Roles
    public function index()
    {
        $roles = Rol::all()->map(fn($r) => [
            'id' => $r->id_rol,
            'nombre' => $r->nombre_role ?? $r->nombre_rol,
            'descripcion' => $r->descripcion ?? 'Sin descripción',
        ])->toArray();

        $usuarios = User::with(['rol', 'persona'])->get()->map(fn($u) => [
            'id' => $u->id_usuario,
            'nombre_completo' => $u->persona ? $u->persona->nombre_completo : $u->nombre_usuario,
            'usuario' => $u->nombre_usuario,
            'correo' => $u->correo,
            'dni' => $u->persona ? $u->persona->ci : 'N/A',
            'telefono' => $u->persona ? $u->persona->telefono : 'N/A',
            'estado' => $u->estado ?? 'Activo',
            'rol' => $u->rol ? $u->rol->nombre_rol : 'Sin rol',
            'id_rol' => $u->id_rol,
        ])->toArray();

        return view('Usuario_Seguridad_y_Auditoria.usuario_y_Roles', compact('roles', 'usuarios'));
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string|max:255|unique:usuario,nombre_usuario',
            'correo' => 'required|email|unique:usuario,correo',
            'id_rol' => 'required|exists:rol,id_rol',
            'password' => 'required|string|min:6|confirmed',
            'nombre_completo' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        // Crear primero la persona
        $nombres = explode(' ', $request->nombre_completo, 2);
        $nombre = $nombres[0];
        $apellido = $nombres[1] ?? '';

        $persona = Persona::create([
            'ci' => $request->dni,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
        ]);

        $user = User::create([
            'nombre_usuario' => $request->nombre_usuario,
            'correo' => $request->correo,
            'id_rol' => $request->id_rol,
            'contraseña' => bcrypt($request->password),
            'estado' => $request->estado,
            'id_persona' => $persona->id_persona,
            'fecha_creacion' => now(),
        ]);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

    // Actualizar usuario existente
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombre_usuario' => 'required|string|max:255|unique:usuario,nombre_usuario,' . $user->id_usuario . ',id_usuario',
            'correo' => 'required|email|unique:usuario,correo,' . $user->id_usuario . ',id_usuario',
            'id_rol' => 'required|exists:rol,id_rol',
            'nombre_completo' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        // Actualizar la persona asociada
        if ($user->persona) {
            $nombres = explode(' ', $request->nombre_completo, 2);
            $nombre = $nombres[0];
            $apellido = $nombres[1] ?? '';

            $user->persona->update([
                'ci' => $request->dni,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
            ]);
        }

        $user->update([
            'nombre_usuario' => $request->nombre_usuario,
            'correo' => $request->correo,
            'id_rol' => $request->id_rol,
            'estado' => $request->estado,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $user->update([
                'contraseña' => bcrypt($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $persona = $user->persona;
        
        $user->delete();
        if ($persona) {
            $persona->delete();
        }

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}