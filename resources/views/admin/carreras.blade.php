@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Gestión de Carreras</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Registra y administra las carreras académicas habilitadas en la Facultad.</p>
        </div>
        <button onclick="document.getElementById('modal-carrera').classList.remove('hidden')" class="px-5 py-3 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus mr-1"></i>
            <span>Nueva Carrera</span>
        </button>
    </div>

    <!-- Carreras List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($carreras as $car)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#0d3b66] flex items-center justify-center text-lg shadow-sm">
                            <i class="fa-solid fa-hotel"></i>
                        </div>
                        <span class="text-[10px] font-bold bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded">{{ $car->duracion_anios }} Años de Duración</span>
                    </div>
                    <h3 class="text-sm font-extrabold text-slate-800 mt-4">{{ $car->nombre_carrera }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-2 line-clamp-3 leading-relaxed">{{ $car->descripcion ?? 'Sin descripción disponible.' }}</p>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-6 flex items-center justify-end space-x-2">
                    <button onclick="editCarrera({{ json_encode($car) }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-colors" title="Editar">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <form action="{{ route('admin.carreras.destroy', $car->id_carrera) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta carrera?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Eliminar">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400">No hay carreras registradas.</div>
        @endforelse
    </div>

    <!-- Create/Edit Modal -->
    <div id="modal-carrera" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 id="modal-title" class="text-sm font-bold text-slate-800">Registrar Nueva Carrera</h3>
                <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="carrera-form" action="{{ route('admin.carreras.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div id="method-container"></div> <!-- Dynamic PUT method if editing -->

                <!-- Nombre -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Nombre de la Carrera *</label>
                    <input type="text" name="nombre_carrera" id="form-nombre" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800">
                </div>

                <!-- Duracion -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Duración (en años) *</label>
                    <input type="number" name="duracion_anios" id="form-duracion" required min="1" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800">
                </div>

                <!-- Descripcion -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Descripción</label>
                    <textarea name="descripcion" id="form-descripcion" rows="3" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl font-bold text-xs shadow-md transition-all">Guardar Carrera</button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    function editCarrera(carrera) {
        document.getElementById('modal-title').innerText = 'Editar Carrera';
        document.getElementById('carrera-form').action = '/admin/carreras/' + carrera.id_carrera;
        document.getElementById('method-container').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('form-nombre').value = carrera.nombre_carrera;
        document.getElementById('form-duracion').value = carrera.duracion_anios;
        document.getElementById('form-descripcion').value = carrera.descripcion || '';
        document.getElementById('modal-carrera').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal-carrera').classList.add('hidden');
        document.getElementById('carrera-form').reset();
        document.getElementById('carrera-form').action = '{{ route("admin.carreras.store") }}';
        document.getElementById('method-container').innerHTML = '';
        document.getElementById('modal-title').innerText = 'Registrar Nueva Carrera';
    }
</script>
@endsection
