@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Gestión de Aulas</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Registra y administra los espacios físicos y laboratorios disponibles para el CUP.</p>
        </div>
        <button onclick="document.getElementById('modal-aula').classList.remove('hidden')" class="px-5 py-3 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus mr-1"></i>
            <span>Nueva Aula</span>
        </button>
    </div>

    <!-- Aulas Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($aulas as $au)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-school"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-full">Capacidad: {{ $au->capacidad }}</span>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800 mt-4">Aula/Lab: {{ $au->codigo_aula }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-2"><i class="fa-solid fa-map-pin mr-1.5 text-red-500"></i>{{ $au->ubicacion ?? 'Ubicación no especificada.' }}</p>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-6 flex items-center justify-end">
                    <form action="{{ route('admin.aulas.destroy', $au->id_aula) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta aula?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">No hay aulas registradas.</div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div id="modal-aula" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Registrar Nueva Aula</h3>
                <button onclick="document.getElementById('modal-aula').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.aulas.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Codigo -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Código de Aula *</label>
                    <input type="text" name="codigo_aula" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. Laboratorio 1 - Fis">
                </div>

                <!-- Capacidad -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Capacidad Máxima *</label>
                    <input type="number" name="capacidad" required min="1" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. 40">
                </div>

                <!-- Ubicacion -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Ubicación</label>
                    <input type="text" name="ubicacion" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. Módulo 236, Piso 2">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-aula').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl font-bold text-xs shadow-md transition-all">Guardar Aula</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
