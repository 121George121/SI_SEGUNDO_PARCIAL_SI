<?php

namespace App\Http\Controllers\Logistica_Recursos_y_Reportes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Docente;
use App\Models\Especialidad;
use App\Models\Persona;
use App\Models\Bitacora;
use App\Models\DetalleBitacora;
use Illuminate\Support\Facades\Auth;

class DocentesController extends Controller
{
    // Listar docentes
    public function index(Request $request)
    {
        $query = Docente::with('persona');

        if ($request->filled('buscar')) {
            $buscar = '%' . $request->buscar . '%';
            $query->whereHas('persona', function($q) use ($buscar) {
                $q->where('nombre', 'ILIKE', $buscar)
                  ->orWhere('apellido', 'ILIKE', $buscar)
                  ->orWhere('ci', 'ILIKE', $buscar)
                  ->orWhere('correo', 'ILIKE', $buscar);
            });
        }

        $docentes = $query->orderBy('id_docente', 'asc')->paginate(10);
        $personas = Persona::whereDoesntHave('docente')->get();
        $especialidades = Especialidad::all();

        return view('Logistica_Recursos_y_Reportes.docente', compact('docentes', 'personas', 'especialidades'));
    }

    // Registrar un nuevo docente
    public function registrar(Request $request)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'Id_persona'=>'required|integer|unique:docente,id_docente',
            'anio_servicio'=>'required|integer|min:0',
            'estado'=>'required|in:Activo,Inactivo'
        ]);

        $docente = Docente::create([
            'id_docente' => $request->Id_persona,
            'anio_servicio' => $request->anio_servicio,
            'estado' => $request->estado
        ]);

        // Asignar especialidad si se provee
        if ($request->filled('id_especialidad')) {
            $docente->especialidades()->syncWithoutDetaching($request->id_especialidad);
        }

        $this->registrarBitacora('Registrar Docente','Docente registrado: '.$docente->id_docente);

        return redirect()->back()->with('success','Docente registrado correctamente');
    }

    // Actualizar información de docente
    public function actualizar(Request $request, Docente $docente)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'anio_servicio'=>'required|integer|min:0',
            'estado'=>'required|in:Activo,Inactivo'
        ]);

        $docente->anio_servicio = $request->anio_servicio;
        $docente->estado = $request->estado;
        $docente->save();

        if ($request->filled('id_especialidad')) {
            $docente->especialidades()->sync([$request->id_especialidad]);
        }

        $this->registrarBitacora('Actualizar Docente','Docente actualizado: '.$docente->id_docente);

        return redirect()->back()->with('success','Docente actualizado correctamente');
    }

    // Eliminar docente
    public function destroy(Docente $docente)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $id = $docente->id_docente;
        $docente->delete();

        $this->registrarBitacora('Eliminar Docente','Docente eliminado: '.$id);

        return redirect()->back()->with('success','Docente eliminado correctamente');
    }

    // Asignar especialidad a docente
    public function asignarEspecialidad(Request $request, Docente $docente)
    {
        $rol = Auth::user()->rol->nombre_rol ?? '';
        if(!in_array($rol,['Administrador','SuperAdministrador'])){
            abort(403,'No autorizado');
        }

        $request->validate([
            'Id_especialidad'=>'required|integer|exists:especialidad,id_especialidad'
        ]);

        $docente->especialidades()->syncWithoutDetaching($request->Id_especialidad);

        $this->registrarBitacora('Asignar Especialidad','Especialidad '.$request->Id_especialidad.' asignada al docente '.$docente->id_docente);

        return redirect()->back()->with('success','Especialidad asignada correctamente');
    }

    // Registrar acciones en bitácora
    private function registrarBitacora(string $accion, string $descripcion)
    {
        $bitacora = Bitacora::create([
            'tipo'=>'Gestión de Docentes',
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