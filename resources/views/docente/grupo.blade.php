@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title / Nav Back Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <a href="{{ route('docente.dashboard') }}" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors border border-slate-100">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800">Grupo: {{ $grupo->sigla_grupo }}</h1>
                <p class="text-xs text-slate-400 font-semibold mt-0.5">Gestión de calificaciones y asistencias para los estudiantes de este grupo.</p>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('docente.asistencia.view', $grupo->id_grupo) }}" class="px-5 py-3 bg-[#0d3b66] hover:bg-[#002855] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
                <i class="fa-solid fa-calendar-check mr-1"></i>
                <span>Tomar Asistencia</span>
            </a>
            <button onclick="document.getElementById('modal-evaluacion').classList.remove('hidden')" class="px-5 py-3 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
                <i class="fa-solid fa-plus mr-1"></i>
                <span>Crear Evaluación</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT: Evaluations list (2 cols on large screen) -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Evaluaciones Planificadas</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($evaluaciones as $eval)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 flex flex-col justify-between hover:shadow-sm transition-shadow">
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold inline-block bg-blue-50 text-blue-600">
                                        Evaluación #{{ $eval->numero_evaluacion }}
                                    </span>
                                    <span class="text-xs font-black text-[#c1121f] bg-red-50 px-2 py-0.5 rounded">{{ $eval->porcentaje }}%</span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 mt-4">Materia: {{ $eval->materia->nombre_materia ?? 'General' }}</h4>
                                <div class="flex items-center space-x-2 text-[10px] text-slate-400 font-bold mt-2">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span>Fecha: {{ \Carbon\Carbon::parse($eval->fecha)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <div class="border-t border-slate-200/60 pt-4 mt-6 flex items-center justify-between">
                                <a href="{{ route('docente.notas.view', [$grupo->id_grupo, $eval->id_evaluacion]) }}" class="px-3 py-1.5 bg-[#0d3b66] hover:bg-[#002855] text-white rounded-lg text-[10px] font-bold shadow flex items-center space-x-1.5">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span>Cargar Notas</span>
                                </a>

                                <form action="{{ route('docente.evaluacion.destroy', $eval->id_evaluacion) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta evaluación y todas sus calificaciones registradas?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Eliminar">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-slate-400 text-xs font-semibold">No se han registrado evaluaciones todavía. Crea una para empezar a calificar.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT: Student List in the Group -->
        <div class="space-y-6">
            
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 overflow-hidden">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-sm">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800">Alumnos en el Grupo ({{ $postulantes->count() }})</h3>
                </div>

                <div class="divide-y divide-slate-100 max-h-[450px] overflow-y-auto pr-1">
                    @forelse($postulantes as $post)
                        <div class="py-3 flex items-center justify-between text-xs font-semibold">
                            <div>
                                <div class="font-bold text-slate-800">{{ $post->persona->nombre_completo }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">CI: {{ $post->persona->ci }}</div>
                            </div>
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-500">Postulante</span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">No hay postulantes asignados a este grupo.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Create Evaluation Modal -->
    <div id="modal-evaluacion" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Crear Evaluación para Grupo</h3>
                <button onclick="document.getElementById('modal-evaluacion').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('docente.evaluacion.store', $grupo->id_grupo) }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Materia -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Materia de la Evaluación *</label>
                    <select name="id_materia" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                        <option value="">Seleccione materia</option>
                        @foreach($materias as $m)
                            <option value="{{ $m->id_materia }}">{{ $m->nombre_materia }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Numero Evaluacion -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Número de Evaluación *</label>
                    <input type="number" name="numero_evaluacion" required min="1" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. 1">
                </div>

                <!-- Porcentaje Weight -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Porcentaje de Nota (Peso %) *</label>
                    <input type="number" name="porcentaje" required min="1" max="100" step="0.5" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. 30">
                    <p class="text-[9px] text-slate-400 font-semibold leading-tight">La suma de todos los pesos en el grupo no debe superar el 100%.</p>
                </div>

                <!-- Fecha -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Fecha de Evaluación *</label>
                    <input type="date" name="fecha" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-evaluacion').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl font-bold text-xs shadow-md transition-all">Registrar Evaluación</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
