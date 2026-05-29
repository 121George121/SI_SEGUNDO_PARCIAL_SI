@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Validación de Pagos y Transacciones</h1>
        <p class="text-xs text-slate-400 font-semibold mt-1">Verifica los comprobantes bancarios cargados por los estudiantes y emite comprobantes oficiales.</p>
    </div>

    <!-- Pagos Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">Postulante</th>
                        <th class="py-4 px-6 text-right">Monto (Bs.)</th>
                        <th class="py-4 px-6">Método de Pago</th>
                        <th class="py-4 px-6 text-center">Estado Pago</th>
                        <th class="py-4 px-6">Comprobante Oficial</th>
                        <th class="py-4 px-6">Observaciones / Imagen</th>
                        <th class="py-4 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($pagos as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $p->inscripcion->postulante->persona->nombre_completo }}</div>
                                <div class="text-[10px] text-slate-400 mt-0.5">Código Insc: {{ $p->inscripcion->codigo_inscripcion }}</div>
                            </td>
                            <td class="py-4 px-6 text-right font-extrabold text-slate-800">{{ number_format($p->monto, 2) }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $p->metodo_pago }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block
                                    @if($p->estado_pago === 'Pagado') bg-emerald-50 text-emerald-600
                                    @elseif($p->estado_pago === 'Pendiente') bg-amber-50 text-amber-600
                                    @else bg-rose-50 text-rose-600 @endif">
                                    {{ $p->estado_pago }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                @if($p->comprobante)
                                    <div class="font-bold text-slate-800">{{ $p->comprobante->tipo_comprobante }}</div>
                                    <div class="text-[10px] text-blue-600 font-bold mt-0.5">{{ $p->comprobante->numero_comprobante }}</div>
                                @else
                                    <span class="text-xs text-slate-400 font-bold">No Emitido</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-400 max-w-xs">
                                <div>{{ $p->observaciones ?? 'Sin observaciones.' }}</div>
                                @if(str_contains($p->observaciones ?? '', '.jpg') || str_contains($p->observaciones ?? '', '.png') || str_contains($p->observaciones ?? '', '.jpeg'))
                                    @php
                                        // Simple regex/string parsing to extract filename
                                        preg_match('/[0-9]+_[^,\s]+/i', $p->observaciones, $matches);
                                        $imgName = $matches[0] ?? null;
                                    @endphp
                                    @if($imgName)
                                        <a href="/uploads/payments/{{ $imgName }}" target="_blank" class="mt-2 text-red-500 font-bold flex items-center hover:underline text-[10px]">
                                            <i class="fa-regular fa-image mr-1"></i> Ver Comprobante Cargado
                                        </a>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($p->estado_pago === 'Pendiente')
                                    <button onclick="openApproveModal({{ json_encode($p) }})" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-[10px] shadow transition-all">
                                        Validar Pago
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-400 font-bold">Procesado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">No hay pagos registrados para validar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="modal-pagos" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-md overflow-hidden shadow-2xl">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Procesar Transacción Financiera</h3>
                <button onclick="document.getElementById('modal-pagos').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="pagos-form" action="" method="POST" class="p-6 space-y-4">
                @csrf

                <!-- Summary Info box -->
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                    <div class="font-bold text-slate-500 uppercase tracking-wider text-[9px]">Postulante</div>
                    <div id="modal-pago-postulante" class="font-bold text-slate-800 mt-0.5"></div>
                    
                    <div class="font-bold text-slate-500 uppercase tracking-wider text-[9px] mt-3">Monto de Matrícula</div>
                    <div id="modal-pago-monto" class="font-extrabold text-[#c1121f] mt-0.5"></div>
                </div>

                <!-- Decisión -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Decisión *</label>
                    <select name="estado_pago" required class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800 bg-white">
                        <option value="">Seleccione decisión</option>
                        <option value="Pagado">Aprobar y Emitir Factura</option>
                        <option value="Rechazado">Rechazar Transacción</option>
                    </select>
                </div>

                <!-- Observaciones -->
                <div class="space-y-1.5">
                    <label class="text-xs font-bold text-slate-700">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#c1121f] text-xs font-semibold text-slate-800" placeholder="Ej. Aprobado, transacción verificada en cuenta fiscal."></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-pagos').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-md transition-all">Procesar Transacción</button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    function openApproveModal(pago) {
        document.getElementById('modal-pago-postulante').innerText = pago.inscripcion.postulante.persona.nombre_completo;
        document.getElementById('modal-pago-monto').innerText = pago.monto + ' Bs.';
        document.getElementById('pagos-form').action = '/admin/pagos/' + pago.id_pago + '/aprobar';
        document.getElementById('modal-pagos').classList.remove('hidden');
    }
</script>
@endsection
