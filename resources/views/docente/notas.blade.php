@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title / Nav Back Section -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('docente.grupo.view', $grupo->id_grupo) }}" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors border border-slate-100">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Cargar Notas</h1>
            <p class="text-xs text-slate-400 font-semibold mt-0.5">Grupo: {{ $grupo->sigla_grupo }} | Evaluación #{{ $evaluacion->numero_evaluacion }} ({{ $evaluacion->porcentaje }}% de la nota final)</p>
        </div>
    </div>

    <!-- Notes Form and Table -->
    <form action="{{ route('docente.notas.store', [$grupo->id_grupo, $evaluacion->id_evaluacion]) }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        @csrf

        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="text-xs text-slate-500 font-bold flex items-center">
                <i class="fa-solid fa-circle-info mr-1.5 text-blue-600"></i>
                <span>Las calificaciones se registran en una escala de 0 a 100 puntos. Los promedios finales se recalculan al guardar.</span>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Guardar Calificaciones</span>
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">Postulante</th>
                        <th class="py-4 px-6">CI</th>
                        <th class="py-4 px-6 text-center" style="width: 180px;">Nota (0 - 100)</th>
                        <th class="py-4 px-6 text-center">Estado Académico Anterior</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($postulantes as $post)
                        @php
                            $registeredGrade = $notas->get($post->id_postulante);
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-800">{{ $post->persona->nombre_completo }}</td>
                            <td class="py-4 px-6 text-slate-500">{{ $post->persona->ci }}</td>
                            <td class="py-4 px-6 text-center">
                                <input type="number" name="notas[{{ $post->id_postulante }}]" min="0" max="100" step="0.1"
                                    value="{{ $registeredGrade ? $registeredGrade->nota : '' }}"
                                    class="w-32 px-3 py-1.5 border border-slate-200 rounded-lg text-center font-bold text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                                    placeholder="0 - 100">
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($registeredGrade)
                                    <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block
                                        @if($registeredGrade->estado_academico === 'Aprobado') bg-emerald-50 text-emerald-600
                                        @else bg-rose-50 text-rose-600 @endif">
                                        {{ $registeredGrade->estado_academico }} ({{ $registeredGrade->nota }} pts)
                                    </span>
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold">Sin calificar</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">No hay postulantes en este grupo para calificar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-slate-100 flex items-center justify-end bg-slate-50/50">
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Guardar Calificaciones</span>
            </button>
        </div>

    </form>

</div>
@endsection
