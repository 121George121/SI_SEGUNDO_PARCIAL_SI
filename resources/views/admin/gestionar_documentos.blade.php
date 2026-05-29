@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">

    {{-- ── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Gestión de Documentos</h1>
            <p class="text-xs text-slate-400 font-semibold">
                Administra los documentos requisito del proceso de admisión CUP.
            </p>
        </div>
        <button onclick="openCreateModal()"
            class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2 self-start">
            <i class="fa-solid fa-circle-plus text-sm"></i>
            <span>Nuevo Documento</span>
        </button>
    </div>

    {{-- ── KPIs ────────────────────────────────────────────────────────── --}}
    @php
        $total      = $documentos->count();
        $pendientes = $documentos->where('estado','Pendiente')->count();
        $validados  = $documentos->where('estado','Validado')->count();
        $rechazados = $documentos->where('estado','Rechazado')->count();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $total }}</p>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total documentos</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $pendientes }}</p>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pendientes</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $validados }}</p>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Validados</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center space-x-4 hover:shadow-md transition-all">
            <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-slate-800">{{ $rechazados }}</p>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rechazados</span>
            </div>
        </div>
    </div>

    {{-- ── TABLA + BUSCADOR ─────────────────────────────────────────────── --}}
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">

        {{-- Header de tarjeta --}}
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-black text-slate-800">Listado de Documentos Requisito</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">
                    Documentos registrados en el sistema como requisitos de admisión.
                </p>
            </div>
            {{-- Buscador --}}
            <div class="relative w-full sm:max-w-xs">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="search-doc" oninput="filterDocs()"
                    placeholder="Buscar documento..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-700 placeholder-slate-400 transition-all">
            </div>
        </div>

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="docs-table">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Tipo de Documento</th>
                        <th class="py-4 px-6">Nombre</th>
                        <th class="py-4 px-6">Estado</th>
                        <th class="py-4 px-6">Observación</th>
                        <th class="py-4 px-6">Fecha Registro</th>
                        <th class="py-4 px-6">Fecha Validación</th>
                        <th class="py-4 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700" id="docs-tbody">
                    @forelse($documentos as $doc)
                        <tr class="hover:bg-slate-50/60 transition-colors doc-row">
                            {{-- ID --}}
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg font-bold text-[10px]">
                                    #{{ $doc->id_documento }}
                                </span>
                            </td>

                            {{-- Tipo --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-file-alt text-[10px]"></i>
                                    </div>
                                    <span class="font-bold text-slate-800">{{ $doc->tipo_documento }}</span>
                                </div>
                            </td>

                            {{-- Nombre --}}
                            <td class="py-4 px-6 text-slate-600 font-medium max-w-[200px]">
                                <span class="truncate block" title="{{ $doc->nombre }}">{{ $doc->nombre }}</span>
                            </td>

                            {{-- Estado --}}
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold inline-block
                                    @if($doc->estado === 'Validado')   bg-emerald-50 text-emerald-600 border border-emerald-100
                                    @elseif($doc->estado === 'Pendiente') bg-amber-50 text-amber-600 border border-amber-100
                                    @else bg-rose-50 text-rose-600 border border-rose-100 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block mr-1
                                        @if($doc->estado === 'Validado') bg-emerald-500
                                        @elseif($doc->estado === 'Pendiente') bg-amber-500
                                        @else bg-rose-500 @endif"></span>
                                    {{ $doc->estado }}
                                </span>
                            </td>

                            {{-- Observación --}}
                            <td class="py-4 px-6 text-slate-400 font-medium max-w-[180px]">
                                <span class="truncate block" title="{{ $doc->observacion ?? '—' }}">
                                    {{ $doc->observacion ? \Str::limit($doc->observacion, 40) : '—' }}
                                </span>
                            </td>

                            {{-- Fecha Registro --}}
                            <td class="py-4 px-6 text-slate-500 font-medium">
                                {{ $doc->fecha_registro ? \Carbon\Carbon::parse($doc->fecha_registro)->format('d/m/Y') : '—' }}
                            </td>

                            {{-- Fecha Validación --}}
                            <td class="py-4 px-6 text-slate-500 font-medium">
                                {{ $doc->fecha_validacion ? \Carbon\Carbon::parse($doc->fecha_validacion)->format('d/m/Y') : '—' }}
                            </td>

                            {{-- Acciones --}}
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="openEditModal({{ $doc->id_documento }}, '{{ addslashes($doc->tipo_documento) }}', '{{ addslashes($doc->nombre) }}', '{{ $doc->estado }}', '{{ addslashes($doc->observacion ?? '') }}', '{{ $doc->fecha_registro }}', '{{ $doc->fecha_validacion }}')"
                                        class="w-8 h-8 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center transition-colors" title="Editar">
                                        <i class="fa-solid fa-pencil text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.gestionar.documentos.destroy', $doc->id_documento) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este documento requisito?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center transition-colors" title="Eliminar">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center">
                                <div class="flex flex-col items-center space-y-3">
                                    <div class="w-16 h-16 rounded-3xl bg-slate-100 flex items-center justify-center">
                                        <i class="fa-solid fa-folder-open text-slate-400 text-2xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">No hay documentos registrados aún.</p>
                                    <button onclick="openCreateModal()"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition-all">
                                        Crear primer documento
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer tabla --}}
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-[10px] font-semibold text-slate-400">
            <span>Total: <strong class="text-slate-600">{{ $total }}</strong> documento(s) registrados</span>
            <span class="text-[10px] text-slate-300">{{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}</span>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: CREAR DOCUMENTO                                                  --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div id="modal-crear-doc" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[92vh] border border-slate-100 overflow-hidden">

        {{-- Modal header --}}
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-folder-plus"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Registrar Documento Requisito</h3>
                    <p class="text-[10px] text-slate-400 font-semibold">Complete los campos para crear un nuevo requisito.</p>
                </div>
            </div>
            <button onclick="closeCreateModal()"
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition-all">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.gestionar.documentos.store') }}" method="POST" class="p-6 overflow-y-auto space-y-5 flex-1">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Tipo de Documento --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-tag text-slate-400 mr-1"></i>Tipo de Documento *
                    </label>
                    <select name="tipo_documento" required
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 bg-white">
                        <option value="">Seleccione un tipo...</option>
                        <option value="Obligatorio">Obligatorio</option>
                        <option value="Opcional">Opcional</option>
                        <option value="Complementario">Complementario</option>
                        <option value="Legal">Legal</option>
                        <option value="Académico">Académico</option>
                        <option value="Médico">Médico</option>
                    </select>
                </div>

                {{-- Estado --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-circle-half-stroke text-slate-400 mr-1"></i>Estado *
                    </label>
                    <select name="estado" required
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 bg-white">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Validado">Validado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                {{-- Nombre --}}
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-file-lines text-slate-400 mr-1"></i>Nombre del Documento *
                    </label>
                    <input type="text" name="nombre" required
                        placeholder="Ej. Fotocopia de Cédula de Identidad"
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-400">
                </div>

                {{-- Observación --}}
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-comment-dots text-slate-400 mr-1"></i>Observación
                    </label>
                    <textarea name="observacion" rows="3"
                        placeholder="Descripción adicional o instrucciones para este requisito..."
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-400 resize-none"></textarea>
                </div>

                {{-- Fecha Registro --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-calendar-plus text-slate-400 mr-1"></i>Fecha de Registro *
                    </label>
                    <input type="date" name="fecha_registro" required
                        value="{{ date('Y-m-d') }}"
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                {{-- Fecha Validación --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-calendar-check text-slate-400 mr-1"></i>Fecha de Validación
                    </label>
                    <input type="date" name="fecha_validacion"
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

            </div>

            {{-- Acciones --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeCreateModal()"
                    class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Guardar Documento</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════════ --}}
{{-- MODAL: EDITAR DOCUMENTO                                                  --}}
{{-- ═══════════════════════════════════════════════════════════════════════ --}}
<div id="modal-editar-doc" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl w-full max-w-2xl shadow-2xl flex flex-col max-h-[92vh] border border-slate-100 overflow-hidden">

        {{-- Modal header --}}
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-folder-pen"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Editar Documento Requisito</h3>
                    <p class="text-[10px] text-slate-400 font-semibold">Modifica los campos del documento seleccionado.</p>
                </div>
            </div>
            <button onclick="closeEditModal()"
                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition-all">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        {{-- Form --}}
        <form id="form-editar-doc" method="POST" class="p-6 overflow-y-auto space-y-5 flex-1">
            @csrf
            @method('PUT')

            {{-- ID (solo lectura) --}}
            <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-2xl border border-slate-100">
                <i class="fa-solid fa-fingerprint text-slate-400"></i>
                <span class="text-xs font-bold text-slate-500">ID Documento:</span>
                <span id="edit-id-display" class="text-xs font-black text-blue-600">#—</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Tipo de Documento --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-tag text-slate-400 mr-1"></i>Tipo de Documento *
                    </label>
                    <select id="edit-tipo" name="tipo_documento" required
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs font-semibold text-slate-800 bg-white">
                        <option value="Obligatorio">Obligatorio</option>
                        <option value="Opcional">Opcional</option>
                        <option value="Complementario">Complementario</option>
                        <option value="Legal">Legal</option>
                        <option value="Académico">Académico</option>
                        <option value="Médico">Médico</option>
                    </select>
                </div>

                {{-- Estado --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-circle-half-stroke text-slate-400 mr-1"></i>Estado *
                    </label>
                    <select id="edit-estado" name="estado" required
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs font-semibold text-slate-800 bg-white">
                        <option value="Pendiente">Pendiente</option>
                        <option value="Validado">Validado</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                {{-- Nombre --}}
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-file-lines text-slate-400 mr-1"></i>Nombre del Documento *
                    </label>
                    <input type="text" id="edit-nombre" name="nombre" required
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs font-semibold text-slate-800">
                </div>

                {{-- Observación --}}
                <div class="md:col-span-2 space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-comment-dots text-slate-400 mr-1"></i>Observación
                    </label>
                    <textarea id="edit-observacion" name="observacion" rows="3"
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs font-semibold text-slate-800 resize-none"></textarea>
                </div>

                {{-- Fecha Registro --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-calendar-plus text-slate-400 mr-1"></i>Fecha de Registro *
                    </label>
                    <input type="date" id="edit-fecha-registro" name="fecha_registro" required
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs font-semibold text-slate-800">
                </div>

                {{-- Fecha Validación --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">
                        <i class="fa-solid fa-calendar-check text-slate-400 mr-1"></i>Fecha de Validación
                    </label>
                    <input type="date" id="edit-fecha-validacion" name="fecha_validacion"
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-400 text-xs font-semibold text-slate-800">
                </div>

            </div>

            {{-- Acciones --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="closeEditModal()"
                    class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Guardar Cambios</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── MODAL CREAR ─────────────────────────────────────────────────────────
    function openCreateModal() {
        document.getElementById('modal-crear-doc').classList.remove('hidden');
    }
    function closeCreateModal() {
        document.getElementById('modal-crear-doc').classList.add('hidden');
    }

    // ── MODAL EDITAR ────────────────────────────────────────────────────────
    function openEditModal(id, tipo, nombre, estado, observacion, fechaReg, fechaVal) {
        // Rellena el formulario con los datos del row
        document.getElementById('edit-id-display').textContent = '#' + id;
        document.getElementById('edit-tipo').value             = tipo;
        document.getElementById('edit-nombre').value           = nombre;
        document.getElementById('edit-estado').value           = estado;
        document.getElementById('edit-observacion').value      = observacion;
        document.getElementById('edit-fecha-registro').value   = fechaReg ? fechaReg.substring(0,10) : '';
        document.getElementById('edit-fecha-validacion').value = fechaVal ? fechaVal.substring(0,10) : '';

        // Actualiza la action del form con el ID correcto
        document.getElementById('form-editar-doc').action = '/admin/gestionar-documentos/' + id;

        document.getElementById('modal-editar-doc').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('modal-editar-doc').classList.add('hidden');
    }

    // ── CERRAR MODAL CON ESC ─────────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCreateModal();
            closeEditModal();
        }
    });

    // ── BUSCADOR ────────────────────────────────────────────────────────────
    function filterDocs() {
        const q = document.getElementById('search-doc').value.toLowerCase();
        document.querySelectorAll('#docs-tbody .doc-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }
</script>
@endsection
