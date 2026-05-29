@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Gestión de Materias</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Registra y administra las materias o contenidos curriculares evaluados en el CUP.</p>
        </div>
        <button onclick="document.getElementById('modal-materia').classList.remove('hidden')" class="px-5 py-3 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus mr-1"></i>
            <span>Nueva Materia</span>
        </button>
    </div>

    <!-- Materias Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($materias as $mat)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full">Créditos: {{ $mat->creditos }}</span>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800 mt-4">{{ $mat->nombre_materia }}</h3>
                    <p class="text-xs text-slate-400 font-bold mt-2">Código: <span class="text-[#0d3b66]">{{ $mat->codigo_materia }}</span></p>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-6 flex items-center justify-end">
                    <form action="{{ route('admin.materias.destroy', $mat->id_materia) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta materia?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">No hay materias registradas.</div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div id="modal-materia" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Registrar Nueva Materia</h3>
                <button onclick="document.getElementById('modal-materia').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.materias.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Nombre -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Nombre de la Materia *</label>
                    <input type="text" name="nombre_materia" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. Introducción a la Programación">
                </div>

                <!-- Codigo -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Código de Materia *</label>
                    <input type="text" name="codigo_materia" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. INF-110">
                </div>

                <!-- Creditos -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Créditos *</label>
                    <input type="number" name="creditos" required min="1" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. 5">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-materia').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl font-bold text-xs shadow-md transition-all">Guardar Materia</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
