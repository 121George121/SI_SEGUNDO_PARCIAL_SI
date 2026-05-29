@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    
    <!-- Header Section / Back Link -->
    <div class="space-y-4">
        <a href="{{ route('admin.inscripciones') }}" class="inline-flex items-center text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            <span>Volver a la lista de postulantes</span>
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Detalle de Inscripción</h1>
                <p class="text-xs text-slate-400 font-semibold mt-1">Consulta y gestiona el proceso del postulante, valida sus documentos y aprueba pagos.</p>
            </div>
            
            <div class="flex items-center space-x-2">
                <button onclick="openEditModal({{ json_encode(['id_inscripcion' => $inscripcion->id_inscripcion, 'ci' => $persona->ci, 'nombre' => $persona->nombre, 'apellido' => $persona->apellido, 'fecha_nacimiento' => $persona->fecha_nacimiento, 'correo' => $persona->correo, 'telefono' => $persona->telefono, 'direccion' => $persona->direccion, 'carrera_principal' => $carreraPrincipal ? $carreraPrincipal->id_carrera : '', 'carrera_secundaria' => $carreraSecundaria ? $carreraSecundaria->id_carrera : '']) }})" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center space-x-2">
                    <i class="fa-solid fa-user-gear text-slate-400"></i>
                    <span>Modificar Datos</span>
                </button>

                @if($inscripcion->estado !== 'Validado')
                <form action="{{ route('admin.inscripciones.validate', $inscripcion->id_inscripcion) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center space-x-2">
                        <i class="fa-solid fa-user-check"></i>
                        <span>Validar Inscripción</span>
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Sub-tabs (Same as list view but with Inscription active) -->
    <div class="flex items-center space-x-12 bg-white px-8 py-4 rounded-3xl border border-slate-100 shadow-sm mb-6">
        <a href="{{ route('admin.inscripciones') }}" class="flex items-center space-x-3 pb-3 border-b-2 border-transparent text-slate-400 font-bold text-sm hover:text-slate-650 transition-all focus:outline-none">
            <i class="fa-solid fa-list-check text-slate-400"></i>
            <span>Lista de Postulantes</span>
        </a>
        <a href="#" class="flex items-center space-x-3 pb-3 border-b-2 border-blue-600 text-blue-600 font-extrabold text-sm transition-all focus:outline-none">
            <i class="fa-solid fa-user-pen text-blue-500"></i>
            <span>Inscripción</span>
        </a>
        <a href="#section-documentos" class="flex items-center space-x-3 pb-3 border-b-2 border-transparent text-slate-400 font-bold text-sm hover:text-slate-650 transition-all focus:outline-none">
            <i class="fa-solid fa-file-invoice text-slate-400"></i>
            <span>Documentos</span>
        </a>
        <a href="#section-pagos" class="flex items-center space-x-3 pb-3 border-b-2 border-transparent text-slate-400 font-bold text-sm hover:text-slate-650 transition-all focus:outline-none">
            <i class="fa-solid fa-credit-card text-slate-400"></i>
            <span>Pago</span>
        </a>
    </div>

    @php
        $words = explode(' ', $persona->nombre_completo);
        $initials = '';
        if (count($words) >= 2) {
            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        } else if (count($words) == 1) {
            $initials = strtoupper(substr($words[0], 0, 2));
        } else {
            $initials = 'PO';
        }
        
        // Dynamic Inscription Status Label
        $insLabel = 'En proceso';
        $insColor = 'bg-blue-50 text-blue-700 border-blue-100';
        if ($inscripcion->estado === 'Validado') {
            $insLabel = 'Inscripción Completada';
            $insColor = 'bg-emerald-50 text-emerald-700 border-emerald-100';
        }
    @endphp

    <!-- 1. Datos del Postulante Card -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6 pb-6 border-b border-slate-100">
            <!-- Avatar -->
            <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xl flex-shrink-0">
                {{ $initials }}
            </div>
            <!-- Name & Header Info -->
            <div class="flex-1 flex flex-col justify-center">
                <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $persona->nombre_completo }}</h2>
                <div class="flex flex-col md:flex-row md:items-center justify-between mt-2 text-xs text-slate-500 font-semibold gap-2">
                    <div class="flex items-center gap-x-6">
                        <span>ID Postulante: <strong class="text-slate-700">{{ $inscripcion->codigo_inscripcion }}</strong></span>
                        <span class="text-slate-300">•</span>
                        <span>CI: <strong class="text-slate-700">{{ $persona->ci }}</strong></span>
                    </div>
                    <div class="flex items-center text-slate-700 font-bold">
                        <i class="fa-regular fa-calendar text-blue-500 mr-2 text-sm"></i>
                        <span>Fecha de Inscripción: {{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 py-8">
            <div class="space-y-3">
                <i class="fa-regular fa-calendar text-blue-500 text-lg"></i>
                <div class="space-y-0.5">
                    <span class="text-slate-500 font-bold block text-[11px]">Fecha de Nacimiento</span>
                    <strong class="text-slate-900 text-sm block font-black">{{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}</strong>
                </div>
            </div>
            <div class="space-y-3">
                <i class="fa-regular fa-envelope text-blue-500 text-lg"></i>
                <div class="space-y-0.5">
                    <span class="text-slate-500 font-bold block text-[11px]">Correo Electrónico</span>
                    <strong class="text-slate-900 text-sm block font-black truncate" title="{{ $persona->correo }}">{{ $persona->correo }}</strong>
                </div>
            </div>
            <div class="space-y-3">
                <i class="fa-solid fa-phone-flip text-blue-500 text-lg"></i>
                <div class="space-y-0.5">
                    <span class="text-slate-500 font-bold block text-[11px]">Teléfono</span>
                    <strong class="text-slate-900 text-sm block font-black">{{ $persona->telefono ?? 'No registrado' }}</strong>
                </div>
            </div>
            <div class="space-y-3">
                <i class="fa-solid fa-location-dot text-blue-500 text-lg"></i>
                <div class="space-y-0.5">
                    <span class="text-slate-500 font-bold block text-[11px]">Dirección</span>
                    <strong class="text-slate-900 text-sm block font-black truncate" title="{{ $persona->direccion }}">{{ $persona->direccion ?? 'No registrada' }}</strong>
                </div>
            </div>
        </div>

        <!-- Details Grid Row 2 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-8 border-t border-slate-100">
            <!-- Carrera Principal -->
            <div class="lg:border-r border-slate-100">
                <span class="text-slate-500 font-bold block text-[11px] mb-1 flex items-center">
                    <i class="fa-regular fa-bookmark text-blue-500 mr-2 text-base"></i>
                    Carrera Principal (1° Prioridad)
                </span>
                <strong class="text-slate-900 text-sm block font-black ml-6">
                    {{ $carreraPrincipal ? $carreraPrincipal->nombre_carrera : 'Sin registrar' }}
                </strong>
            </div>
            <!-- Carrera Secundaria -->
            <div class="lg:border-r border-slate-100 lg:px-6">
                <span class="text-slate-500 font-bold block text-[11px] mb-1 flex items-center">
                    <i class="fa-regular fa-bookmark text-slate-400 mr-2 text-base"></i>
                    Carrera Secundaria (2° Prioridad)
                </span>
                <strong class="text-slate-900 text-sm block font-black ml-6">
                    {{ $carreraSecundaria ? $carreraSecundaria->nombre_carrera : 'Sin registrar' }}
                </strong>
            </div>
            <!-- Estado -->
            <div class="lg:px-6">
                <span class="text-slate-500 font-bold block text-[11px] mb-1 flex items-center">
                    <i class="fa-regular fa-circle-check text-emerald-500 mr-2 text-base"></i>
                    Estado del Proceso
                </span>
                <strong class="text-emerald-600 text-sm block font-black uppercase ml-6">
                    {{ strtoupper($insLabel) }}
                </strong>
            </div>
        </div>
    </div>

    <!-- 2. Documentos Section -->
    <div id="section-documentos" class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-sm font-black text-slate-800">Documentos</h3>
                <p class="text-[10px] text-slate-400 font-semibold">Valida los requisitos documentales presentados por el postulante.</p>
            </div>
            <!-- Badges Legend -->
            <div class="flex flex-wrap items-center gap-3 text-[9px] font-bold uppercase tracking-wider">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Obligatorio</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-400"></span> Optativo</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Validado</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-500"></span> Pendiente</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Rechazado</span>
            </div>
        </div>

        <div class="overflow-hidden">
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="py-4 px-6">DOCUMENTO</th>
                            <th class="py-4 px-6">TIPO</th>
                            <th class="py-4 px-6">OBLIGATORIO</th>
                            <th class="py-4 px-6">ESTADO</th>
                            <th class="py-4 px-6">FECHA DE CARGA</th>
                            <th class="py-4 px-6 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-semibold text-slate-700">
                        @foreach($documentos as $doc)
                            @php
                                $badgeColor = 'bg-slate-50 text-slate-500 border border-slate-100';
                                if ($doc->estado === 'Validado') {
                                    $badgeColor = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                } elseif ($doc->estado === 'Pendiente') {
                                    $badgeColor = 'bg-amber-50 text-amber-700 border border-amber-100';
                                } elseif ($doc->estado === 'Rechazado') {
                                    $badgeColor = 'bg-rose-50 text-rose-700 border border-rose-100';
                                }
                                
                                $tipoColor = ($doc->tipo === 'Obligatorio') ? 'text-blue-600 bg-blue-50 border border-blue-100' : 'text-slate-500 bg-slate-50 border border-slate-250';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center space-x-3">
                                        <i class="fa-solid @if($doc->archivo) fa-file-pdf text-red-500 @else fa-file text-slate-300 @endif text-lg"></i>
                                        <div>
                                            <span class="font-extrabold text-slate-800 block">{{ $doc->nombre }}</span>
                                            @if($doc->archivo)
                                                <span class="text-[10px] text-slate-450 font-mono block mt-0.5">{{ $doc->archivo }}</span>
                                            @else
                                                <span class="text-[10px] text-rose-450 font-bold block mt-0.5">No presentado</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $tipoColor }}">
                                        {{ $doc->tipo }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-slate-500">{{ $doc->obligatorio }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold inline-flex items-center gap-1.5 {{ $badgeColor }}">
                                        @if($doc->estado === 'Validado')
                                            <i class="fa-solid fa-circle-check text-[9px]"></i>
                                            Validado
                                        @elseif($doc->estado === 'Rechazado')
                                            <i class="fa-solid fa-circle-xmark text-[9px]"></i>
                                            Rechazado
                                        @else
                                            <i class="fa-solid fa-clock text-[9px]"></i>
                                            {{ $doc->estado }}
                                        @endif
                                    </span>
                                    @if($doc->observacion)
                                        <div class="text-[10px] text-rose-500 mt-1 font-semibold block" title="{{ $doc->observacion }}">
                                            Obs: {{ Str::limit($doc->observacion, 30) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-slate-450">{{ $doc->fecha_carga }}</td>
                                <td class="py-4 px-6 text-center">
                                    <button onclick="openValidateDocModal({{ json_encode($doc) }})" class="w-8 h-8 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center transition-colors mx-auto" title="Validar Documento">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-[10px] text-slate-400 font-semibold mt-4">
                Mostrando 1 a {{ $documentos->count() }} de {{ $documentos->count() }} documentos
            </div>
        </div>
    </div>

    <!-- 3. Estado de Pago Section -->
    <div id="section-pagos" class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-sm font-black text-slate-800">Estado de Pago</h3>
            <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Control de depósito de arancel de derecho a examen CUP.</p>
        </div>

        @if($pago)
            @php
                $pagoStateLabel = 'Pendiente';
                $pagoBadgeColor = 'bg-blue-50 text-blue-700 border-blue-100';
                if ($pago->estado_pago === 'Pagado') {
                    $pagoStateLabel = 'Pagado';
                    $pagoBadgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-100';
                } elseif ($pago->estado_pago === 'Rechazado') {
                    $pagoStateLabel = 'Rechazado';
                    $pagoBadgeColor = 'bg-rose-50 text-rose-700 border-rose-100';
                }
            @endphp
            <!-- Payment Data Table/Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 text-xs pb-6">
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block">N° Transacción</span>
                    <strong class="text-slate-800 text-sm block font-extrabold font-mono">
                        {{ $pago->comprobante ? $pago->comprobante->numero_comprobante : ($pago->id_pago ? 'TRX-' . date('Y') . '-' . sprintf('%04d', $pago->id_pago) : '-') }}
                    </strong>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block">Monto (Bs.)</span>
                    <strong class="text-slate-800 text-sm block font-black">
                        {{ number_format($pago->monto, 2) }}
                    </strong>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block">Fecha de Generación</span>
                    <strong class="text-slate-850 text-sm block font-extrabold">
                        {{ $pago->fecha_registro ? \Carbon\Carbon::parse($pago->fecha_registro)->format('d/m/Y H:i') : \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') . ' 10:30' }}
                    </strong>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block">Fecha de Pago</span>
                    <strong class="text-slate-850 text-sm block font-extrabold">
                        {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : '-' }}
                    </strong>
                </div>
                <div class="space-y-1">
                    <span class="text-slate-400 font-semibold block">Estado de Pago</span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold border inline-block mt-0.5 {{ $pagoBadgeColor }}">
                        {{ $pagoStateLabel }}
                    </span>
                </div>
            </div>

            <!-- Banner alerts and actions based on payment state -->
            @if($pago->estado_pago === 'Pagado')
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3.5 text-emerald-800 text-xs font-semibold">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0 text-sm shadow-inner">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 text-sm">El pago ha sido realizado correctamente.</p>
                            <p class="text-slate-500 text-[10px] mt-0.5">La orden de matrícula ha sido emitida con el Comprobante correspondiente.</p>
                        </div>
                    </div>
                    <button onclick="openComprobanteModal({{ json_encode($pago->comprobante ?? ['numero_comprobante' => 'FAC-' . rand(100000, 999999), 'tipo_comprobante' => 'Factura de Admisión', 'fecha_emision' => $pago->fecha_pago ?? now()->toDateString(), 'monto' => $pago->monto]) }})" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center space-x-2">
                        <i class="fa-solid fa-receipt text-slate-400"></i>
                        <span>Ver Comprobante</span>
                    </button>
                </div>
            @elseif($pago->estado_pago === 'Pendiente')
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-3.5 text-blue-800 text-xs font-semibold">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0 text-sm shadow-inner animate-pulse">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                        <div>
                            <p class="font-extrabold text-slate-800 text-sm">El pago está registrado como PENDIENTE.</p>
                            <p class="text-slate-500 text-[10px] mt-0.5">El comprobante bancario requiere validación de facturación.</p>
                        </div>
                    </div>
                    <button onclick="openApprovePaymentModal({{ json_encode($pago) }})" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center space-x-2">
                        <i class="fa-solid fa-credit-card"></i>
                        <span>Validar Pago</span>
                    </button>
                </div>
            @endif
        @else
            <!-- No Payment Generated Yet -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-3.5 text-slate-800 text-xs font-semibold">
                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 flex-shrink-0 text-sm shadow-inner">
                        <i class="fa-solid fa-circle-exclamation"></i>
                    </div>
                    <div>
                        <p class="font-extrabold text-slate-800 text-sm">No se ha generado ninguna orden de pago.</p>
                        <p class="text-slate-400 text-[10px] mt-0.5">Debe emitir la orden de cobro para habilitar el derecho de examen del postulante.</p>
                    </div>
                </div>
                <form action="{{ route('admin.inscripciones.payment', $inscripcion->id_inscripcion) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-[#0066ff] hover:bg-[#0052cc] text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center space-x-2">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span>Generar Pago</span>
                    </button>
                </form>
            </div>
        @endif
    </div>

</div>

<!-- ------------------------------------------------------------- -->
<!-- MODAL: EDITAR POSTULANTE (FROM DETAIL)                         -->
<!-- ------------------------------------------------------------- -->
<div id="modal-edit-inscripcion" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-edit-card">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-650">
                    <i class="fa-solid fa-user-pen text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Modificar Datos de Inscripción</h3>
                    <p class="text-[10px] text-slate-400 font-semibold">Edita los datos personales y preferencias del postulante.</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 hover:bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form id="form-edit-inscripcion" method="POST" class="flex-1 overflow-y-auto p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CI -->
                <div class="space-y-1.5">
                    <label for="edit_ci" class="text-xs font-bold text-slate-700">Cédula de Identidad (CI) *</label>
                    <input type="text" name="ci" id="edit_ci" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Correo -->
                <div class="space-y-1.5">
                    <label for="edit_correo" class="text-xs font-bold text-slate-700">Correo Electrónico *</label>
                    <input type="email" name="correo" id="edit_correo" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Nombre -->
                <div class="space-y-1.5">
                    <label for="edit_nombre" class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                    <input type="text" name="nombre" id="edit_nombre" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Apellido -->
                <div class="space-y-1.5">
                    <label for="edit_apellido" class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                    <input type="text" name="apellido" id="edit_apellido" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Fecha Nacimiento -->
                <div class="space-y-1.5">
                    <label for="edit_fecha_nacimiento" class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" id="edit_fecha_nacimiento" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Telefono -->
                <div class="space-y-1.5">
                    <label for="edit_telefono" class="text-xs font-bold text-slate-700">Teléfono / Celular</label>
                    <input type="text" name="telefono" id="edit_telefono" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Direccion -->
                <div class="col-span-full space-y-1.5">
                    <label for="edit_direccion" class="text-xs font-bold text-slate-700">Dirección de Domicilio</label>
                    <input type="text" name="direccion" id="edit_direccion" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Carrera Principal -->
                <div class="space-y-1.5">
                    <label for="edit_carrera_principal" class="text-xs font-bold text-slate-700">Carrera Principal *</label>
                    <select name="carrera_principal_id" id="edit_carrera_principal" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="">Seleccione carrera</option>
                        @foreach($allCarreras as $c)
                            <option value="{{ $c->id_carrera }}">{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Carrera Secundaria -->
                <div class="space-y-1.5">
                    <label for="edit_carrera_secundaria" class="text-xs font-bold text-slate-700">Carrera Secundaria *</label>
                    <select name="carrera_secundaria_id" id="edit_carrera_secundaria" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="">Seleccione carrera</option>
                        @foreach($allCarreras as $c)
                            <option value="{{ $c->id_carrera }}">{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Modal Actions Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3 flex-shrink-0">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-[#0066ff] hover:bg-[#0052cc] text-white rounded-xl font-bold text-xs shadow-md transition-all">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- ------------------------------------------------------------- -->
<!-- MODAL: VALIDAR DOCUMENTO                                      -->
<!-- ------------------------------------------------------------- -->
<div id="modal-validate-doc" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-validate-doc-card">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-650">
                    <i class="fa-solid fa-file-shield text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800" id="val-doc-title">Validar Documento</h3>
                    <p class="text-[10px] text-slate-400 font-semibold" id="val-doc-subtitle">Cambia el estado de validez del requisito del postulante.</p>
                </div>
            </div>
            <button onclick="closeValidateDocModal()" class="w-8 h-8 hover:bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form action="{{ route('admin.inscripciones.documento.validate', $inscripcion->id_inscripcion) }}" method="POST" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="id_postulante" value="{{ $postulante->id_postulante }}">
            <input type="hidden" name="documento_nombre" id="val_documento_nombre" value="">
            
            <div class="space-y-4">
                <!-- Document File Link -->
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between text-xs">
                    <span class="font-extrabold text-slate-700 flex items-center">
                        <i class="fa-regular fa-file-lines mr-2.5 text-blue-500 text-base"></i>
                        Archivo Subido
                    </span>
                    <a id="val-doc-download" href="#" target="_blank" class="px-3.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-[#0066ff] rounded-xl font-bold font-mono text-[10px] flex items-center">
                        <i class="fa-solid fa-download mr-1.5 text-xs"></i>
                        DESCARGAR
                    </a>
                </div>

                <!-- Estado Selector -->
                <div class="space-y-1.5">
                    <label for="val_estado" class="text-xs font-bold text-slate-700">Estado de Validación *</label>
                    <select name="estado" id="val_estado" onchange="checkObsField(this.value);" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="Validado">Validado</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="Rechazado">Rechazado</option>
                    </select>
                </div>

                <!-- Observacion -->
                <div class="space-y-1.5" id="obs-container">
                    <label for="val_observacion" class="text-xs font-bold text-slate-700">Notas u Observación</label>
                    <textarea name="observacion" id="val_observacion" rows="3" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Detalle el motivo de la observación si es rechazado..."></textarea>
                </div>
            </div>
            
            <!-- Modal Actions Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3 flex-shrink-0">
                <button type="button" onclick="closeValidateDocModal()" class="px-5 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition-all">Actualizar Estado</button>
            </div>
        </form>
    </div>
</div>

<!-- ------------------------------------------------------------- -->
<!-- MODAL: DETALLE COMPROBANTE                                    -->
<!-- ------------------------------------------------------------- -->
<div id="modal-comprobante" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-comprobante-card">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <h3 class="text-sm font-black text-slate-800">Comprobante de Pago</h3>
            <button onclick="closeComprobanteModal()" class="w-8 h-8 hover:bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6 space-y-6 text-xs text-slate-700">
            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-4 shadow-inner text-center">
                <i class="fa-solid fa-receipt text-blue-600 text-4xl mb-1"></i>
                <div class="space-y-1">
                    <span class="text-[9px] uppercase font-bold text-slate-400 block tracking-widest" id="comp-type">Factura</span>
                    <strong class="text-slate-800 text-lg font-mono block font-black" id="comp-number">FAC-897341</strong>
                </div>
            </div>

            <div class="space-y-3 font-semibold">
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <span class="text-slate-400">Fecha de Emisión:</span>
                    <span class="text-slate-700 font-extrabold" id="comp-date">28/05/2026</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <span class="text-slate-400">Concepto de Pago:</span>
                    <span class="text-slate-700 font-extrabold">Derecho Examen de Admisión CUP</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <span class="text-slate-400">Método de Validación:</span>
                    <span class="text-slate-700 font-extrabold">Acreditación Administrativa</span>
                </div>
                <div class="flex items-center justify-between py-2 text-sm pt-4">
                    <span class="text-slate-400 font-bold">Monto Total:</span>
                    <span class="text-emerald-600 font-black text-base" id="comp-amount">350.00 Bs</span>
                </div>
            </div>

            <button onclick="closeComprobanteModal()" class="w-full py-3.5 bg-slate-900 hover:bg-slate-850 text-white rounded-xl font-bold text-xs shadow-md transition-all text-center">
                Cerrar Detalle
            </button>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------- -->
<!-- MODAL: APROBAR PAGO                                           -->
<!-- ------------------------------------------------------------- -->
<div id="modal-approve-payment" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-approve-payment-card">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <h3 class="text-sm font-black text-slate-800">Validar y Registrar Pago</h3>
            <button onclick="closeApprovePaymentModal()" class="w-8 h-8 hover:bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form id="form-approve-payment" method="POST" class="p-6 space-y-4">
            @csrf
            
            <div class="space-y-4">
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold">Arancel Examen:</span>
                        <strong class="text-slate-850 font-black">350.00 Bs</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-bold">Método de Pago:</span>
                        <strong class="text-slate-800 font-extrabold" id="pay-method">Transferencia</strong>
                    </div>
                </div>

                <!-- Estado Selector -->
                <div class="space-y-1.5">
                    <label for="pay_estado" class="text-xs font-bold text-slate-700">Estado de Aprobación *</label>
                    <select name="estado_pago" id="pay_estado" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="Pagado">Aprobado (Generar Factura)</option>
                        <option value="Rechazado">Rechazado (Registrar Rechazo)</option>
                    </select>
                </div>

                <!-- Observaciones -->
                <div class="space-y-1.5">
                    <label for="pay_obs" class="text-xs font-bold text-slate-700">Observaciones</label>
                    <textarea name="observaciones" id="pay_obs" rows="3" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800" placeholder="Notas administrativas sobre el abono..."></textarea>
                </div>
            </div>
            
            <!-- Modal Actions Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3 flex-shrink-0">
                <button type="button" onclick="closeApprovePaymentModal()" class="px-5 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-blue-650 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition-all">Procesar Transacción</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Edit Modal Actions
    function openEditModal(data) {
        const modal = document.getElementById('modal-edit-inscripcion');
        const card = document.getElementById('modal-edit-card');
        
        document.getElementById('edit_ci').value = data.ci || '';
        document.getElementById('edit_correo').value = data.correo || '';
        document.getElementById('edit_nombre').value = data.nombre || '';
        document.getElementById('edit_apellido').value = data.apellido || '';
        document.getElementById('edit_fecha_nacimiento').value = data.fecha_nacimiento || '';
        document.getElementById('edit_telefono').value = data.telefono || '';
        document.getElementById('edit_direccion').value = data.direccion || '';
        document.getElementById('edit_carrera_principal').value = data.carrera_principal || '';
        document.getElementById('edit_carrera_secundaria').value = data.carrera_secundaria || '';
        
        const form = document.getElementById('form-edit-inscripcion');
        form.action = `/admin/inscripciones/${data.id_inscripcion}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeEditModal() {
        const modal = document.getElementById('modal-edit-inscripcion');
        const card = document.getElementById('modal-edit-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Validate Document Modal Actions
    function openValidateDocModal(doc) {
        const modal = document.getElementById('modal-validate-doc');
        const card = document.getElementById('modal-validate-doc-card');
        
        document.getElementById('val-doc-title').innerText = 'Validar ' + doc.nombre;
        document.getElementById('val_documento_nombre').value = doc.nombre;
        
        // Use backend enums directly (Validado, Rechazado, Pendiente)
        let statusVal = doc.estado;
        
        document.getElementById('val_estado').value = statusVal;
        document.getElementById('val_observacion').value = doc.observacion || '';
        
        // Handle download button link and availability
        const dlBtn = document.getElementById('val-doc-download');
        if (doc.archivo) {
            dlBtn.classList.remove('pointer-events-none', 'opacity-50');
            dlBtn.href = `/uploads/documents/${doc.archivo}`;
        } else {
            dlBtn.classList.add('pointer-events-none', 'opacity-50');
            dlBtn.href = '#';
        }
        
        checkObsField(statusVal);

        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeValidateDocModal() {
        const modal = document.getElementById('modal-validate-doc');
        const card = document.getElementById('modal-validate-doc-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
    
    function checkObsField(val) {
        const obsContainer = document.getElementById('obs-container');
        // We can make it optional but visually distinct
    }

    // Comprobante Modal Actions
    function openComprobanteModal(comp) {
        const modal = document.getElementById('modal-comprobante');
        const card = document.getElementById('modal-comprobante-card');
        
        document.getElementById('comp-type').innerText = comp.tipo_comprobante || 'Factura';
        document.getElementById('comp-number').innerText = comp.numero_comprobante || '';
        
        // Format Date
        let dateVal = comp.fecha_emision || '';
        if (dateVal.includes('-')) {
            const parts = dateVal.split('-');
            if (parts.length === 3) {
                dateVal = `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        }
        document.getElementById('comp-date').innerText = dateVal;
        document.getElementById('comp-amount').innerText = (parseFloat(comp.monto || 350).toFixed(2)) + ' Bs';

        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeComprobanteModal() {
        const modal = document.getElementById('modal-comprobante');
        const card = document.getElementById('modal-comprobante-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Approve Payment Modal Actions
    function openApprovePaymentModal(pago) {
        const modal = document.getElementById('modal-approve-payment');
        const card = document.getElementById('modal-approve-payment-card');
        
        document.getElementById('pay-method').innerText = pago.metodo_pago || 'Transferencia';
        document.getElementById('pay_obs').value = pago.observaciones || '';
        
        const form = document.getElementById('form-approve-payment');
        form.action = `/admin/pagos/${pago.id_pago}/aprobar`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeApprovePaymentModal() {
        const modal = document.getElementById('modal-approve-payment');
        const card = document.getElementById('modal-approve-payment-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
@endsection
