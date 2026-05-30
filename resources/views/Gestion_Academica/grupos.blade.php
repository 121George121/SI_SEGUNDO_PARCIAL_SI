@extends('layouts.app')

@section('content')
<div x-data="{ 
    openEditModal: false, 
    editGroup: { id: '', sigla: '', carrera_id: '', modalidad_id: '', turno_id: '', capacidad: 40, estado: 'Activo', descripcion: '' }
}" class="space-y-8">

    <!-- Page Header and Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-5">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Grupos</h1>
            <nav class="flex text-sm text-slate-400 mt-2 font-semibold">
                <a href="{{ route('dashboard') }}" class="hover:text-red-500 transition-colors">Dashboard</a>
                <span class="mx-2 text-slate-300">/</span>
                <span class="hover:text-red-500 transition-colors">Grupos</span>
                <span class="mx-2 text-slate-300">/</span>
                <span class="text-slate-600 font-bold">Nuevo Grupo</span>
            </nav>
        </div>
    </div>

    <!-- 1. REGISTRAR NUEVO GRUPO CARD (Full Width) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm p-6 space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Registrar Nuevo Grupo</h2>
            </div>
            <div class="bg-orange-50 border border-orange-100 rounded-xl px-4 py-3 flex items-center gap-3 text-xs text-orange-800 font-medium max-w-2xl">
                <i class="fa-solid fa-circle-info text-orange-500 text-base flex-shrink-0"></i>
                <span>Los grupos registrados estarán disponibles para asignar aulas, modalidad, turnos y gestionar su capacidad.</span>
            </div>
        </div>

        <form action="{{ route('admin.grupos.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Row 1: 4 columns -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Nombre del Grupo -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nombre del Grupo *</label>
                    <input type="text" name="sigla_grupo" placeholder="Ej: Grupo A - Ingeniería" value="{{ old('sigla_grupo') }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium">
                </div>

                <!-- Carrera / Facultad -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Carrera / Facultad *</label>
                    <select name="id_carrera" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-600">
                        <option value="">Seleccione una carrera</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modalidad -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Modalidad *</label>
                    <select name="id_modalidad" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-600">
                        <option value="">Seleccione modalidad</option>
                        @foreach($modalidades as $mod)
                            <option value="{{ $mod->id_modalidad }}">{{ $mod->nombre_modalidad }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Turno -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Turno *</label>
                    <select name="id_turno" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-600">
                        <option value="">Seleccione turno</option>
                        @foreach($turnos as $turno)
                            <option value="{{ $turno->id_turno }}">{{ $turno->nombre_turno }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 2: 4 columns -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Capacidad Máxima -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Capacidad Máxima *</label>
                    <input type="number" name="capacidad_max" placeholder="Ej: 40" min="1" value="40" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium">
                </div>

                <!-- Capacidad Actual (disabled) -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Capacidad Actual</label>
                    <input type="text" placeholder="Ej: 0" disabled
                        class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-400 cursor-not-allowed outline-none font-medium">
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estado</label>
                    <select name="estado" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-700">
                        <option value="Activo">🟢 Activo</option>
                        <option value="Inactivo">🔴 Inactivo</option>
                    </select>
                </div>

                <!-- Descripción (Opcional) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                    <textarea name="descripcion" placeholder="Información adicional del grupo..." rows="1"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all resize-none font-medium h-[44px]"></textarea>
                </div>
            </div>

            <!-- Buttons Row (Aligned Right) -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-50">
                <button type="reset" class="px-6 py-3 rounded-xl text-sm font-bold text-slate-500 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">Cancelar</button>
                <button type="submit" class="px-6 py-3 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 shadow-md shadow-red-600/10 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Guardar Grupo</span>
                </button>
            </div>
        </form>
    </div>

    <!-- 2. GRUPOS REGISTRADOS CARD (Full Width) -->
    <div class="bg-white rounded-2xl border border-slate-150 shadow-sm p-6 space-y-6">
        
        <!-- Table header search & filters -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 border-b border-slate-100 pb-5">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Grupos Registrados</h2>
            </div>

            <form action="{{ route('admin.grupos') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                <!-- Search bar -->
                <div class="relative w-full sm:w-56">
                    <i class="fa-solid fa-magnifying-glass text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 text-sm"></i>
                    <input type="text" name="search" placeholder="Buscar grupo..." value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white focus:ring-2 focus:ring-red-500/15 focus:border-red-500 transition-all w-full font-medium">
                </div>

                <!-- Filter Carrera -->
                <div class="relative w-full sm:w-auto">
                    <select name="carrera_id" onchange="this.form.submit()"
                        class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white transition-all text-slate-600 font-bold w-full">
                        <option value="">Filter por Carrera</option>
                        @foreach($carreras as $c)
                            <option value="{{ $c->id_carrera }}" {{ request('carrera_id') == $c->id_carrera ? 'selected' : '' }}>{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Modalidad -->
                <div class="relative w-full sm:w-auto">
                    <select name="modalidad_id" onchange="this.form.submit()"
                        class="px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:bg-white transition-all text-slate-600 font-bold w-full">
                        <option value="">Filter por Modalidad</option>
                        @foreach($modalidades as $m)
                            <option value="{{ $m->id_modalidad }}" {{ request('modalidad_id') == $m->id_modalidad ? 'selected' : '' }}>{{ $m->nombre_modalidad }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Reset Filters Button -->
                @if(request()->filled('search') || request()->filled('carrera_id') || request()->filled('modalidad_id'))
                    <a href="{{ route('admin.grupos') }}" class="p-2.5 text-slate-400 hover:text-red-500 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors" title="Limpiar Filtros">
                        <i class="fa-solid fa-filter-circle-xmark text-lg"></i>
                    </a>
                @endif
            </form>
        </div>

        <!-- Main groups table -->
        <div class="overflow-x-auto rounded-xl border border-slate-100">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-12">#</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Grupo</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Carrera / Facultad</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Modalidad</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Turno</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Capacidad Máxima</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Capacidad Actual</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Estado</th>
                        <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                    @forelse($grupos as $index => $g)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-5 py-4 text-slate-400 text-center font-bold">{{ $index + 1 }}</td>
                            <td class="px-5 py-4 text-slate-900 font-bold">{{ $g->sigla_grupo }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $g->carrera->nombre_carrera ?? 'Sin Carrera' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $g->modalidad->nombre_modalidad ?? 'Presencial' }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $g->turno->nombre_turno ?? 'Mañana' }}</td>
                            <td class="px-5 py-4 text-center text-slate-800 font-semibold">{{ $g->capacidad_max }}</td>
                            <td class="px-5 py-4 text-center">
                                <!-- Dynamic progress indicators for groups -->
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <span class="font-bold text-slate-900">{{ $g->cant_estudiantes }}</span>
                                    <div class="w-16 bg-slate-100 h-1.5 rounded-full overflow-hidden relative">
                                        @php
                                            $percentage = ($g->capacidad_max > 0) ? min(100, ($g->cant_estudiantes / $g->capacidad_max) * 100) : 0;
                                            $barColor = ($percentage >= 90) ? 'bg-red-500' : (($percentage >= 70) ? 'bg-orange-500' : 'bg-emerald-500');
                                        @endphp
                                        <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold leading-none {{ $g->estado === 'Activo' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $g->estado === 'Activo' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                    {{ $g->estado }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Edit Action -->
                                    <button @click="
                                        editGroup = {
                                            id: '{{ $g->id_grupo }}',
                                            sigla: '{{ $g->sigla_grupo }}',
                                            carrera_id: '{{ $g->id_carrera }}',
                                            modalidad_id: '{{ $g->id_modalidad }}',
                                            turno_id: '{{ $g->id_turno }}',
                                            capacidad: '{{ $g->capacidad_max }}',
                                            estado: '{{ $g->estado }}',
                                            descripcion: '{{ addslashes($g->descripcion) }}'
                                        };
                                        openEditModal = true;
                                    " class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar Grupo">
                                        <i class="fa-solid fa-pencil text-base"></i>
                                    </button>

                                    <!-- Delete Action -->
                                    <form action="{{ route('admin.grupos.destroy', $g->id_grupo) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este grupo?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar Grupo"
                                            {{ $g->cant_estudiantes > 0 ? 'disabled style=opacity:0.4;cursor:not-allowed;' : '' }}>
                                            <i class="fa-solid fa-trash-can text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-8 text-center text-slate-400">
                                <i class="fa-solid fa-people-group text-3xl mb-2 text-slate-350"></i>
                                <p class="font-medium text-sm">No se encontraron grupos registrados.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination and details -->
        <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 font-bold border-t border-slate-100 pt-4 gap-4">
            <span>Mostrando 1 a {{ count($grupos) }} de {{ count($grupos) }} grupos</span>
            
            <div class="flex items-center gap-1">
                <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-angles-left text-[10px]"></i>
                </button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-angle-left text-[10px]"></i>
                </button>
                <button class="w-8 h-8 rounded-lg bg-red-600 text-white flex items-center justify-center shadow-sm">
                    1
                </button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                    2
                </button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-50 transition-colors">
                    3
                </button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-angle-right text-[10px]"></i>
                </button>
                <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-slate-50 transition-colors">
                    <i class="fa-solid fa-angles-right text-[10px]"></i>
                </button>
            </div>
        </div>

    </div>

    <!-- ALPINEJS EDIT MODAL -->
    <div x-show="openEditModal" 
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
        style="display: none;"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden"
            @click.away="openEditModal = false"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="scale-95 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-95 opacity-0">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800">Modificar Grupo</h3>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="'Editar parámetros del grupo: ' + editGroup.sigla"></p>
                </div>
                <button @click="openEditModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form :action="'{{ url('/admin/grupos') }}/' + editGroup.id" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nombre del Grupo *</label>
                    <input type="text" name="sigla_grupo" x-model="editGroup.sigla" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Carrera / Facultad *</label>
                    <select name="id_carrera" x-model="editGroup.carrera_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-600">
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Modalidad *</label>
                        <select name="id_modalidad" x-model="editGroup.modalidad_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-600">
                            @foreach($modalidades as $mod)
                                <option value="{{ $mod->id_modalidad }}">{{ $mod->nombre_modalidad }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Turno *</label>
                        <select name="id_turno" x-model="editGroup.turno_id" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-600">
                            @foreach($turnos as $turno)
                                <option value="{{ $turno->id_turno }}">{{ $turno->nombre_turno }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Capacidad Máx *</label>
                        <input type="number" name="capacidad_max" x-model="editGroup.capacidad" min="1" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Estado</label>
                        <select name="estado" x-model="editGroup.estado" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all font-medium text-slate-700">
                            <option value="Activo">🟢 Activo</option>
                            <option value="Inactivo">🔴 Inactivo</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                    <textarea name="descripcion" x-model="editGroup.descripcion" placeholder="Información adicional..." rows="2"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all resize-none font-medium"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="button" @click="openEditModal = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-slate-500 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-bold text-white bg-red-600 hover:bg-red-700 transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i>
                        <span>Actualizar Grupo</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection
