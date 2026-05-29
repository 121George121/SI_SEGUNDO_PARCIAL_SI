@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    
    @php
        // -------------------------------------------------------------
        // DYNAMIC STEPPER & PROGRESS CALCULATIONS FOR POSTULANTE
        // -------------------------------------------------------------
        
        // Step 1: Inscripción (Always complete since they have an account)
        $insStatus = 'Completada';
        $insClass = 'bg-emerald-500 text-white shadow-lg';
        
        // Step 2: Documentos
        $docCount = $documentos->count();
        $docsAprobados = $documentos->where('estado', 'Aprobado')->count() + $documentos->where('estado', 'Validado')->count();
        $docsObservados = $documentos->where('estado', 'Rechazado')->count() + $documentos->where('estado', 'Observado')->count();
        $docsEnRevision = $documentos->where('estado', 'En revisión')->count();
        
        $docStatus = 'Pendiente';
        $docClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($docCount > 0) {
            if ($docsAprobados === 7) { // All 7 documents validated
                $docStatus = 'Aprobado';
                $docClass = 'bg-emerald-500 text-white shadow-lg';
            } elseif ($docsObservados > 0) {
                $docStatus = 'Observado';
                $docClass = 'bg-rose-500 text-white shadow-lg';
            } elseif ($docsEnRevision > 0) {
                $docStatus = 'En revisión';
                $docClass = 'bg-amber-500 text-white shadow-lg';
            } else {
                $docStatus = 'En proceso';
                $docClass = 'bg-blue-500 text-white shadow-lg';
            }
        }
        
        // Step 3: Validación (Admin checks and validates general inscription)
        $valStatus = 'Pendiente';
        $valClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($inscripcion && $inscripcion->estado === 'Validado') {
            $valStatus = 'Validado';
            $valClass = 'bg-emerald-500 text-white shadow-lg';
        } elseif ($inscripcion && $inscripcion->estado === 'Activo') {
            $valStatus = 'En revisión';
            $valClass = 'bg-amber-500 text-white shadow-lg';
        }
        
        // Step 4: Pago
        $pagoStatus = 'Pendiente';
        $pagoClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($pago) {
            if ($pago->estado_pago === 'Pagado') {
                $pagoStatus = 'Pagado';
                $pagoClass = 'bg-emerald-500 text-white shadow-lg';
            } elseif ($pago->estado_pago === 'Pendiente') {
                $pagoStatus = 'Pendiente';
                $pagoClass = 'bg-blue-500 text-white shadow-lg';
            }
        }
        
        // Progress percentage calculation
        $progressPercent = 25; // 25% for inscription
        if ($docStatus === 'Aprobado') $progressPercent += 25;
        if ($valStatus === 'Validado') $progressPercent += 25;
        if ($pagoStatus === 'Pagado') $progressPercent += 25;
        
        // Dynamic Inscription Status Label for Header
        $insHeaderLabel = 'Inscripción en proceso';
        $insHeaderColor = 'bg-blue-50 text-blue-700 border border-blue-100';
        if ($inscripcion && $inscripcion->estado === 'Validado') {
            $insHeaderLabel = 'Inscripción Completa';
            $insHeaderColor = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
        }
    @endphp

    <!-- Welcome Hero section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="space-y-1">
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Mi Progreso de Admisión</h1>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $insHeaderColor }}">
                    {{ $insHeaderLabel }}
                </span>
            </div>
            <p class="text-xs font-semibold text-slate-400">Verifica el estado actual de tu proceso. Todos los campos se encuentran bloqueados para edición.</p>
        </div>
        <div class="text-xs text-slate-400 font-semibold">
            Código: <strong class="text-slate-700 font-mono">{{ $inscripcion ? $inscripcion->codigo_inscripcion : 'N/A' }}</strong>
        </div>
    </div>

    <!-- VISUAL TIMELINE STEPPER -->
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-4 relative max-w-4xl mx-auto">
            
            <!-- Connection Lines -->
            <div class="absolute top-[28px] left-[10%] right-[10%] h-0.5 bg-slate-100 -z-0 hidden md:block"></div>
            <div class="absolute top-[28px] left-[10%] w-[80%] h-0.5 -z-0 hidden md:block">
                <div class="h-full bg-gradient-to-r from-emerald-500 via-blue-500 to-slate-200 transition-all duration-500" style="width: {{ $progressPercent - 25 }}%;"></div>
            </div>

            <!-- Step 1: Inscripción -->
            <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $insClass }} transition-all duration-300">
                    <i class="fa-solid fa-check text-base"></i>
                </div>
                <span class="text-xs font-bold text-slate-700 mt-3 block">1. Inscripción</span>
                <span class="text-[9px] font-bold text-emerald-500 mt-1 block uppercase tracking-wider">{{ $insStatus }}</span>
            </div>

            <!-- Step 2: Documentos -->
            <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $docClass }} transition-all duration-300">
                    @if($docStatus === 'Aprobado')
                        <i class="fa-solid fa-check text-base"></i>
                    @elseif($docStatus === 'Observado')
                        <i class="fa-solid fa-exclamation text-base"></i>
                    @else
                        <span>2</span>
                    @endif
                </div>
                <span class="text-xs font-bold text-slate-700 mt-3 block">2. Documentos</span>
                <span class="text-[9px] font-bold @if($docStatus === 'Aprobado') text-emerald-500 @elseif($docStatus === 'Observado') text-rose-500 @elseif($docStatus === 'En revisión') text-amber-500 @else text-slate-400 @endif mt-1 block uppercase tracking-wider">
                    {{ $docStatus }}
                </span>
            </div>

            <!-- Step 3: Validación -->
            <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $valClass }} transition-all duration-300">
                    @if($valStatus === 'Validado')
                        <i class="fa-solid fa-check text-base"></i>
                    @else
                        <span>3</span>
                    @endif
                </div>
                <span class="text-xs font-bold text-slate-700 mt-3 block">3. Validación Datos</span>
                <span class="text-[9px] font-bold @if($valStatus === 'Validado') text-emerald-500 @elseif($valStatus === 'En revisión') text-amber-500 @else text-slate-400 @endif mt-1 block uppercase tracking-wider">
                    {{ $valStatus }}
                </span>
            </div>

            <!-- Step 4: Pago -->
            <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $pagoClass }} transition-all duration-300">
                    @if($pagoStatus === 'Pagado')
                        <i class="fa-solid fa-check text-base"></i>
                    @else
                        <span>4</span>
                    @endif
                </div>
                <span class="text-xs font-bold text-slate-700 mt-3 block">4. Pago Arancel</span>
                <span class="text-[9px] font-bold @if($pagoStatus === 'Pagado') text-emerald-500 @else text-blue-500 @endif mt-1 block uppercase tracking-wider">
                    {{ $pagoStatus }}
                </span>
            </div>

        </div>
    </div>

    <!-- MAIN GRID FOR POSTULANTE PROGRESS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Datos Personales & Carreras (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Datos Personales Card (Read-only) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-800">Mis Datos Personales</h3>
                    <p class="text-[10px] text-slate-450 font-semibold mt-0.5">Si requiere modificar algún dato, por favor acérquese a las oficinas de admisiones.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CI -->
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Cédula de Identidad (CI)</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed">
                            {{ $persona->ci }}
                        </div>
                    </div>

                    <!-- Correo -->
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Correo Electrónico</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed truncate" title="{{ $persona->correo }}">
                            {{ $persona->correo }}
                        </div>
                    </div>

                    <!-- Nombre -->
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Nombre(s)</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed">
                            {{ $persona->nombre }}
                        </div>
                    </div>

                    <!-- Apellido -->
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Apellido(s)</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed">
                            {{ $persona->apellido }}
                        </div>
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Fecha de Nacimiento</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed">
                            {{ \Carbon\Carbon::parse($persona->fecha_nacimiento)->format('d/m/Y') }}
                        </div>
                    </div>

                    <!-- Telefono -->
                    <div class="space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Teléfono / Celular</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed">
                            {{ $persona->telefono ?? 'No registrado' }}
                        </div>
                    </div>

                    <!-- Direccion -->
                    <div class="col-span-full space-y-1">
                        <span class="text-xs font-bold text-slate-400 block">Dirección</span>
                        <div class="px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-extrabold text-slate-650 cursor-not-allowed">
                            {{ $persona->direccion ?? 'No registrada' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mis Documentos Subidos Card (Read-only) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-800">Requisitos Documentales</h3>
                    <p class="text-[10px] text-slate-450 font-semibold mt-0.5">Control de validación digital de los documentos cargados en el sistema.</p>
                </div>

                <div class="overflow-hidden border border-slate-100 rounded-2xl">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-3.5 px-4">DOCUMENTO</th>
                                <th class="py-3.5 px-4">TIPO</th>
                                <th class="py-3.5 px-4 text-center">ESTADO</th>
                                <th class="py-3.5 px-4">FECHA CARGA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
                            @foreach($documentos as $doc)
                                @php
                                    $badgeColor = 'bg-slate-50 text-slate-500 border border-slate-100';
                                    if ($doc->estado === 'Aprobado' || $doc->estado === 'Validado') {
                                        $badgeColor = 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                    } elseif ($doc->estado === 'En revisión') {
                                        $badgeColor = 'bg-amber-50 text-amber-600 border border-amber-100';
                                    } elseif ($doc->estado === 'Rechazado' || $doc->estado === 'Observado') {
                                        $badgeColor = 'bg-rose-50 text-rose-600 border border-rose-100';
                                    }
                                @endphp
                                <tr>
                                    <td class="py-3.5 px-4">
                                        <div class="font-extrabold text-slate-800">{{ $doc->nombre }}</div>
                                        @if(isset($doc->archivo) && $doc->archivo)
                                            <div class="text-[10px] text-slate-450 mt-0.5 font-mono flex items-center space-x-1">
                                                <i class="fa-solid fa-paperclip text-[9px] text-slate-400"></i>
                                                <span>{{ $doc->archivo }}</span>
                                            </div>
                                        @endif
                                        @if($doc->estado === 'Observado' || $doc->estado === 'Rechazado')
                                            <div class="text-[10px] text-rose-500 font-semibold mt-1">
                                                <strong>Obs:</strong> {{ $doc->observacion ?? 'Corrija el archivo cargado.' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500">{{ $doc->tipo }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block {{ $badgeColor }}">
                                            {{ $doc->estado }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-450">{{ $doc->fecha_carga }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Academic Preferences & Payment Card (1/3 width) -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Carrera Preference Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-800">Preferencia de Carreras</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Tus opciones académicas registradas para el examen.</p>
                </div>

                <div class="space-y-4">
                    <div class="bg-blue-50/50 rounded-2xl p-4 border border-blue-100/50 space-y-1">
                        <span class="text-[9px] uppercase font-bold text-blue-500 tracking-wider">1° Prioridad - Principal</span>
                        <h4 class="text-xs font-black text-slate-800 leading-snug">
                            {{ $carreraPrincipal ? $carreraPrincipal->nombre_carrera : 'Sin registrar' }}
                        </h4>
                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/50 space-y-1">
                        <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">2° Prioridad - Secundaria</span>
                        <h4 class="text-xs font-black text-slate-700 leading-snug">
                            {{ $carreraSecundaria ? $carreraSecundaria->nombre_carrera : 'Sin registrar' }}
                        </h4>
                    </div>
                </div>
            </div>

            <!-- Estado de Pago Card -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-sm font-black text-slate-800">Mi Pago Arancelario</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Estado de transacción del arancel del Examen CUP.</p>
                </div>

                @if($pago)
                    @php
                        $pagoLabel = 'Pendiente';
                        $pagoColor = 'bg-blue-50 text-blue-700 border border-blue-100';
                        
                        if ($pago->estado_pago === 'Pagado') {
                            $pagoLabel = 'Pagado / Aprobado';
                            $pagoColor = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                        }
                    @endphp
                    <div class="space-y-4 text-xs">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 font-semibold">
                            <span class="text-slate-400">N° Orden:</span>
                            <span class="text-slate-850 font-extrabold font-mono">
                                {{ $pago->comprobante ? $pago->comprobante->numero_comprobante : 'TRX-' . date('Y') . '-' . sprintf('%04d', $pago->id_pago) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 font-semibold">
                            <span class="text-slate-400">Arancel Establecido:</span>
                            <span class="text-slate-800 font-extrabold font-mono">Bs. {{ number_format($pago->monto, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 font-semibold">
                            <span class="text-slate-400">Método de Pago:</span>
                            <span class="text-slate-700 font-extrabold">{{ $pago->metodo_pago }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 font-semibold">
                            <span class="text-slate-400">Estado Pago:</span>
                            <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase tracking-wider {{ $pagoColor }}">
                                {{ $pagoLabel }}
                            </span>
                        </div>
                        
                        @if($pago->estado_pago === 'Pagado')
                            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-emerald-800 text-[10px] font-semibold leading-relaxed">
                                <i class="fa-solid fa-circle-check text-emerald-500 mr-1 text-sm"></i>
                                Su pago ha sido procesado exitosamente por administración y su matrícula CUP está habilitada.
                            </div>
                        @else
                            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 text-blue-800 text-[10px] font-semibold leading-relaxed">
                                <i class="fa-solid fa-clock text-blue-500 mr-1 text-sm animate-pulse"></i>
                                Transacción bancaria en validación administrativa de facturas.
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-center text-xs font-semibold">
                        <i class="fa-solid fa-file-invoice-dollar text-slate-400 text-3xl mb-3 block"></i>
                        <span class="text-slate-800 block mb-1">Sin orden de cobro</span>
                        <p class="text-slate-400 text-[10px] font-medium leading-relaxed">
                            No se ha registrado una orden de pago para su cuenta en el sistema. Contacte a admisiones.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection
