@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title / Nav Back Section -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('docente.grupo.view', $grupo->id_grupo) }}" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors border border-slate-100">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Control de Asistencia</h1>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">Grupo: {{ $grupo->sigla_grupo }} | Registra la asistencia diaria para los estudiantes del grupo.</p>
        </div>
    </div>

    <!-- Attendance Form -->
    <form action="{{ route('docente.asistencia.store', $grupo->id_grupo) }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        @csrf

        <div class="p-6 border-b border-slate-100 bg-slate-50/50 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end">
            <!-- Select Subject (Materia) -->
            <div class="space-y-1.5">
                <label for="id_materia" class="text-xs font-bold text-slate-700">Materia *</label>
                <select name="id_materia" id="id_materia" required class="block w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800">
                    <option value="">Seleccione materia</option>
                    @foreach($materias as $m)
                        <option value="{{ $m->id_materia }}" {{ old('id_materia') == $m->id_materia ? 'selected' : '' }}>
                            {{ $m->nombre_materia }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Date (Fecha) -->
            <div class="space-y-1.5">
                <label for="fecha" class="text-xs font-bold text-slate-700">Fecha de Asistencia *</label>
                <input type="date" name="fecha" id="fecha" required value="{{ old('fecha', now()->toDateString()) }}"
                    class="block w-full px-4 py-2 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent text-xs font-semibold text-slate-800">
            </div>

            <!-- Submit Button -->
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-calendar-check mr-1.5"></i>
                <span>Registrar Asistencias</span>
            </button>
        </div>

        <!-- Attendance List Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">Postulante</th>
                        <th class="py-4 px-6">CI</th>
                        <th class="py-4 px-6 text-center">Estado Asistencia</th>
                        <th class="py-4 px-6">Observación</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($postulantes as $post)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $post->persona->nombre_completo }}</td>
                            <td class="py-4 px-6 text-slate-500">{{ $post->persona->ci }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center space-x-6">
                                    <!-- Radio: Presente -->
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="asistencias[{{ $post->id_postulante }}]" value="Presente" checked
                                            class="w-4 h-4 text-emerald-600 focus:ring-emerald-500 border-slate-300">
                                        <span class="text-xs font-bold text-emerald-600">Presente</span>
                                    </label>
                                    
                                    <!-- Radio: Ausente -->
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="asistencias[{{ $post->id_postulante }}]" value="Ausente"
                                            class="w-4 h-4 text-rose-600 focus:ring-rose-500 border-slate-300">
                                        <span class="text-xs font-bold text-rose-600">Ausente</span>
                                    </label>

                                    <!-- Radio: Tarde -->
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="asistencias[{{ $post->id_postulante }}]" value="Tarde"
                                            class="w-4 h-4 text-amber-600 focus:ring-amber-500 border-slate-300">
                                        <span class="text-xs font-bold text-amber-600">Tarde</span>
                                    </label>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <input type="text" name="observaciones[{{ $post->id_postulante }}]"
                                    class="w-full px-3 py-1.5 border border-slate-200 rounded-lg text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                    placeholder="Ej. Justificó tardanza.">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">No hay postulantes en este grupo para registrar asistencias.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 flex items-center justify-end bg-slate-50/50">
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                <i class="fa-solid fa-calendar-check mr-1.5"></i>
                <span>Registrar Asistencias</span>
            </button>
        </div>

    </form>

</div>
@endsection
