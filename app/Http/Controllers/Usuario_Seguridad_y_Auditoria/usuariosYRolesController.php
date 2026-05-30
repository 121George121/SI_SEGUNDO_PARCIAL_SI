<?php

namespace App\Http\Controllers\Usuario_Seguridad_y_Auditoria;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;

class usuariosYRolesController extends Controller
{
    
      //Mostrar vista principal de Usuarios y Roles
    
    public function index()
    {
        
        $roles = Rol::all()->map(fn($r) => [
            'id' => $r->id_rol,
            'nombre' => $r->nombre_rol,
            'descripcion' => $r->descripcion ?? 'Sin descripción',
        ])->toArray();

        
        $usuarios = User::with('rol')->get()->map(fn($u) => [
            'id' => $u->id_usuario,
            'nombre_completo' => $u->nombre_usuario,
            'email' => $u->email,
            'rol' => $u->rol ? $u->rol->nombre_rol : 'Sin rol',
        ])->toArray();

       
        return view('usuarios_roles.index', compact('roles', 'usuarios'));
    }

   
     // Crear un nuevo usuario
     
    public function store(Request $request)
    {
        $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'id_rol' => 'required|exists:rol,id_rol',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'id_rol' => $request->id_rol,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->back()->with('success', 'Usuario creado correctamente.');
    }

   
     // Actualizar usuario existente
     
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nombre_usuario' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_usuario . ',id_usuario',
            'id_rol' => 'required|exists:rol,id_rol',
        ]);

        $user->update([
            'nombre_usuario' => $request->nombre_usuario,
            'email' => $request->email,
            'id_rol' => $request->id_rol,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->back()->with('success', 'Usuario actualizado correctamente.');
    }

   
     // Eliminar usuario
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }
}