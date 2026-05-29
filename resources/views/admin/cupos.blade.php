@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Cupos por Carrera</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Establece el número de vacantes y cupos disponibles por carrera y gestión preuniversitaria.</p>
        </div>
        <button onclick="document.getElementById('modal-cupo').classList.remove('hidden')" class="px-5 py-3 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus mr-1"></i>
            <span>Asignar Cupos</span>
        </button>
    </div>

    <!-- Cupos List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($cupos as $cup)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-slate-100 text-slate-600 px-2.5 py-1 rounded">Gestión: {{ $cup->gestion }}</span>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800 mt-4">{{ $cup->carrera->nombre_carrera }}</h3>
                    
                    <div class="grid grid-cols-3 gap-4 mt-6 text-center border-t border-slate-50 pt-4">
                        <div>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Establecidos</span>
                            <span class="text-sm font-extrabold text-slate-800 block mt-1">{{ $cup->cantidad_cupos }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-[#c1121f] uppercase tracking-wider block">Ocupados</span>
                            <span class="text-sm font-extrabold text-[#c1121f] block mt-1">{{ $cup->cupos_ocupados }}</span>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-emerald-600 uppercase tracking-wider block">Disponibles</span>
                            <span class="text-sm font-extrabold text-emerald-600 block mt-1">{{ $cup->cupos_disponibles }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">No hay cupos asignados a carreras actualmente.</div>
        @endforelse
    </div>

    <!-- Create Modal -->
    <div id="modal-cupo" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Asignar Cupos a Carrera</h3>
                <button onclick="document.getElementById('modal-cupo').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.cupos.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Carrera -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Carrera *</label>
                    <select name="id_carrera" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                        <option value="">Seleccionar Carrera</option>
                        @foreach($carreras as $car)
                            <option value="{{ $car->id_carrera }}">{{ $car->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Gestion -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Gestión Académica *</label>
                    <select name="id_gestion" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                        <option value="">Seleccionar Gestión</option>
                        @foreach($gestiones as $ges)
                            <option value="{{ $ges->id_gestion }}">{{ $ges->anio }} - Período {{ $ges->periodo }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cantidad Cupos -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Cantidad de Cupos a Habilitar *</label>
                    <input type="number" name="cantidad_cupos" required min="1" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. 60">
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-cupo').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl font-bold text-xs shadow-md transition-all">Habilitar Cupos</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
