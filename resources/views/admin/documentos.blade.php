@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Validación de Expedientes y Documentos</h1>
        <p class="text-xs text-slate-400 font-semibold mt-1">Revisa, aprueba o rechaza los documentos cargados por los postulantes para su inscripción.</p>
    </div>

    <!-- Documentos List Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">Postulante</th>
                        <th class="py-4 px-6">Tipo Documento</th>
                        <th class="py-4 px-6">Archivo</th>
                        <th class="py-4 px-6">Fecha Subida</th>
                        <th class="py-4 px-6 text-center">Estado Validación</th>
                        <th class="py-4 px-6">Observación</th>
                        <th class="py-4 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($documentos as $doc)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $doc->postulante->persona->nombre_completo }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">CI: {{ $doc->postulante->persona->ci }}</div>
                            </td>
                            <td class="py-4 px-6 font-bold text-[#0d3b66]">{{ $doc->tipo_documento }}</td>
                            <td class="py-4 px-6">
                                <a href="/uploads/documents/{{ $doc->nombre }}" target="_blank" class="text-xs font-bold text-red-600 hover:underline flex items-center space-x-1.5">
                                    <i class="fa-solid fa-file-pdf text-base"></i>
                                    <span>Ver Archivo</span>
                                </a>
                            </td>
                            <td class="py-4 px-6 text-slate-400">{{ \Carbon\Carbon::parse($doc->fecha_registro)->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block
                                    @if($doc->estado === 'Validado') bg-emerald-50 text-emerald-600
                                    @elseif($doc->estado === 'Pendiente') bg-amber-50 text-amber-600
                                    @else bg-rose-50 text-rose-600 @endif">
                                    {{ $doc->estado }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-400 max-w-xs truncate">{{ $doc->observacion ?? 'Sin observaciones.' }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($doc->estado === 'Pendiente')
                                    <button onclick="openValidateModal({{ json_encode($doc) }})" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-[10px] shadow transition-all">
                                        Procesar
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold">Procesado por ID {{ $doc->id_administrador }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No hay documentos registrados para validar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Validation Modal -->
    <div id="modal-validar" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Procesar Documento</h3>
                <button onclick="document.getElementById('modal-validar').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="validar-form" action="" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Postulante Name info -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                    <div class="font-bold text-slate-500 uppercase tracking-wider text-[9px]">Postulante</div>
                    <div id="modal-postulante-name" class="font-bold text-slate-800 mt-0.5"></div>
                    
                    <div class="font-bold text-slate-500 uppercase tracking-wider text-[9px] mt-3">Tipo Documento</div>
                    <div id="modal-doc-type" class="font-bold text-[#0d3b66] mt-0.5"></div>
                </div>

                <!-- Decisión -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Decisión *</label>
                    <select name="estado" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                        <option value="">Seleccione decisión</option>
                        <option value="Validado">Aprobar y Validar</option>
                        <option value="Rechazado">Rechazar Documento</option>
                    </select>
                </div>

                <!-- Observaciones -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Observaciones / Motivo de Rechazo</label>
                    <textarea name="observacion" rows="3" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Escriba observaciones o indicaciones para el postulante."></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-validar').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition-all">Guardar Decisión</button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    function openValidateModal(doc) {
        document.getElementById('modal-postulante-name').innerText = doc.postulante.persona.nombre_completo;
        document.getElementById('modal-doc-type').innerText = doc.tipo_documento;
        document.getElementById('validar-form').action = '/admin/documentos/' + doc.id_documento + '/validar';
        document.getElementById('modal-validar').classList.remove('hidden');
    }
</script>
@endsection
