@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Gestión de Grupos</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Registra y administra los grupos del CUP, vinculando docentes, aulas, horarios y estudiantes.</p>
        </div>
        <button onclick="document.getElementById('modal-grupo').classList.remove('hidden')" class="px-5 py-3 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus mr-1"></i>
            <span>Nuevo Grupo</span>
        </button>
    </div>

    <!-- Grupos List Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">Sigla Grupo</th>
                        <th class="py-4 px-6">Docente</th>
                        <th class="py-4 px-6">Aula</th>
                        <th class="py-4 px-6">Modalidad / Turno</th>
                        <th class="py-4 px-6">Gestión</th>
                        <th class="py-4 px-6 text-center">Capacidad (Estudiantes)</th>
                        <th class="py-4 px-6 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($grupos as $g)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-extrabold text-slate-800">{{ $g->sigla_grupo }}</td>
                            <td class="py-4 px-6 font-bold text-slate-700">{{ $g->docente->persona->nombre_completo }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $g->aula->codigo_aula }}</td>
                            <td class="py-4 px-6 text-slate-400">
                                <div>{{ $g->modalidad->nombre_modalidad }}</div>
                                <div class="text-[10px] text-blue-600 font-bold mt-0.5">{{ $g->turno->nombre_turno }}</div>
                            </td>
                            <td class="py-4 px-6 text-slate-400">{{ $g->gestion->anio }} - Período {{ $g->gestion->periodo }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="font-bold text-slate-800">{{ $g->cant_estudiantes }}</span> / <span class="text-slate-400">{{ $g->capacidad_max }}</span>
                                <div class="w-24 bg-slate-100 rounded-full h-1.5 mx-auto mt-1.5 overflow-hidden">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ ($g->cant_estudiantes / $g->capacidad_max) * 100 }}%"></div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                    {{ $g->estado }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No hay grupos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="modal-grupo" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-lg overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Crear Nuevo Grupo</h3>
                <button onclick="document.getElementById('modal-grupo').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.grupos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Sigla Grupo -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Sigla del Grupo *</label>
                        <input type="text" name="sigla_grupo" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. GRUPO-A">
                    </div>

                    <!-- Capacidad Max -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Capacidad Máxima *</label>
                        <input type="number" name="capacidad_max" required min="1" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. 40">
                    </div>

                    <!-- Aula -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Aula *</label>
                        <select name="id_aula" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Aula</option>
                            @foreach($aulas as $au)
                                <option value="{{ $au->id_aula }}">{{ $au->codigo_aula }} (Capacidad: {{ $au->capacidad }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Docente -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Docente *</label>
                        <select name="id_docente" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Docente</option>
                            @foreach($docentes as $doc)
                                <option value="{{ $doc->id_docente }}">{{ $doc->persona->nombre_completo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Modalidad -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Modalidad *</label>
                        <select name="id_modalidad" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Modalidad</option>
                            @foreach($modalidades as $mod)
                                <option value="{{ $mod->id_modalidad }}">{{ $mod->nombre_modalidad }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Turno -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Turno *</label>
                        <select name="id_turno" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Turno</option>
                            @foreach($turnos as $tu)
                                <option value="{{ $tu->id_turno }}">{{ $tu->nombre_turno }} ({{ $tu->hora_inicio }} - {{ $tu->hora_fin }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gestion -->
                    <div class="col-span-full space-y-1">
                        <label class="text-xs font-bold text-slate-700">Gestión Académica *</label>
                        <select name="id_gestion" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Gestión</option>
                            @foreach($gestiones as $ges)
                                <option value="{{ $ges->id_gestion }}">{{ $ges->anio }} - Período {{ $ges->periodo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-grupo').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl font-bold text-xs shadow-md transition-all">Crear Grupo</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
