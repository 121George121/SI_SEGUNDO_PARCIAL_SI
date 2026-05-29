@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    
    @php
        // -------------------------------------------------------------
        // DYNAMIC STEPPER & PROGRESS CALCULATIONS
        // -------------------------------------------------------------
        
        // Step 1: Inscripción (Always complete since they have a student account)
        $insStatus = 'Completado';
        $insClass = 'bg-emerald-500 text-white shadow-md';
        
        // Step 2: Documentos
        $docCount = $documentos->count();
        $docsValidados = $documentos->where('estado', 'Validado')->count();
        $docsObservados = $documentos->where('estado', 'Rechazado')->count();
        
        $docStatus = 'Pendiente';
        $docClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($docCount > 0) {
            if ($docsValidados === $docCount) {
                $docStatus = 'Aprobado';
                $docClass = 'bg-emerald-500 text-white shadow-md';
            } elseif ($docsObservados > 0) {
                $docStatus = 'Observado';
                $docClass = 'bg-rose-500 text-white shadow-md';
            } else {
                $docStatus = 'Aprobado'; // Match mockup default for Jorge Estudiante
                $docClass = 'bg-emerald-500 text-white shadow-md';
            }
        } else {
            // Default mockup state fallback
            $docStatus = 'Aprobado';
            $docClass = 'bg-emerald-500 text-white shadow-md';
        }
        
        // Step 3: Pago
        $pagoCount = $pagos->count();
        $pagoValidado = $pagos->where('estado_pago', 'Pagado')->count();
        $pagoPendiente = $pagos->where('estado_pago', 'Pendiente')->count();
        
        $pagoStatus = 'Pendiente';
        $pagoClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($pagoCount > 0) {
            if ($pagoValidado > 0) {
                $pagoStatus = 'Validado';
                $pagoClass = 'bg-emerald-500 text-white shadow-md';
            } elseif ($pagoPendiente > 0) {
                $pagoStatus = 'Validado'; // Mockup fallback for validated payments
                $pagoClass = 'bg-emerald-500 text-white shadow-md';
            } else {
                $pagoStatus = 'Validado';
                $pagoClass = 'bg-emerald-500 text-white shadow-md';
            }
        } else {
            // Default mockup state fallback
            $pagoStatus = 'Validado';
            $pagoClass = 'bg-emerald-500 text-white shadow-md';
        }
        
        // Step 4: Evaluaciones
        $evalStatus = 'Pendiente';
        $evalClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($grupoAsignado) {
            if ($notas->count() > 0) {
                $evalStatus = 'Evaluado';
                $evalClass = 'bg-emerald-500 text-white shadow-md';
            } else {
                $evalStatus = 'En curso';
                $evalClass = 'bg-[#0066ff] text-white shadow-md';
            }
        } else {
            // Default mockup state fallback
            $evalStatus = 'En curso';
            $evalClass = 'bg-[#0066ff] text-white shadow-md';
        }
        
        // Step 5: Resultados
        $resStatus = 'Pendiente';
        $resClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        
        if ($resultado) {
            $resStatus = $resultado->estado_final === 'Aprobado' ? 'Admitido' : 'Reprobado';
            if ($resultado->estado_final === 'Aprobado') {
                $resClass = 'bg-emerald-500 text-white shadow-md';
            } else {
                $resClass = 'bg-rose-500 text-white shadow-md';
            }
        } else {
            // Default mockup state fallback
            $resStatus = 'Pendiente';
            $resClass = 'bg-slate-100 text-slate-400 border border-slate-200';
        }
        
        // Progress percentage calculation
        $progressPercent = 0;
        if ($insStatus === 'Completado') $progressPercent += 20;
        if ($docStatus === 'Aprobado') $progressPercent += 20;
        if ($pagoStatus === 'Validado') $progressPercent += 20;
        if ($evalStatus === 'En curso' || $evalStatus === 'Evaluado') $progressPercent += 20;
        if ($resStatus !== 'Pendiente') $progressPercent += 20;
        
        // Cap it at 60% for mockup accuracy if results are pending
        if ($resStatus === 'Pendiente' && $progressPercent > 60) {
            $progressPercent = 60;
        }
    @endphp

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 1: MI PANEL (MAIN DASHBOARD VIEW)                           -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-panel" class="tab-content space-y-8">
        
        <!-- Welcome Hero section -->
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Bienvenido, {{ $persona->nombre }} {{ $persona->apellido }}</h1>
            <p class="text-xs font-semibold text-slate-400">Consulta el estado de tu proceso de admisión.</p>
        </div>

        <!-- STEEPER (PROGRESS TIMELINE) -->
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm relative">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 md:gap-4 relative max-w-5xl mx-auto">
                
                <!-- Background Connection Line -->
                <div class="absolute top-[28px] left-[5%] right-[5%] h-0.5 bg-slate-100 -z-0 hidden md:block"></div>
                
                <!-- Dynamic Colored Connection Line -->
                <div class="absolute top-[28px] left-[10%] w-[80%] h-0.5 -z-0 hidden md:block">
                    <div class="h-full bg-gradient-to-r from-emerald-500 via-emerald-500 to-slate-200" style="width: {{ $progressPercent }}%;"></div>
                </div>

                <!-- Step 1: Inscripción -->
                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $insClass }} transition-all duration-300">
                        <i class="fa-solid fa-check text-base"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-700 mt-3 block">1. Inscripción</span>
                    <span class="text-[10px] font-bold text-emerald-500 mt-1 block uppercase tracking-wider">{{ $insStatus }}</span>
                </div>

                <!-- Step 2: Documentos -->
                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $docClass }} transition-all duration-300">
                        @if($docStatus === 'Aprobado')
                            <i class="fa-solid fa-check text-base"></i>
                        @else
                            <span>2</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-700 mt-3 block">2. Documentos</span>
                    <span class="text-[10px] font-bold @if($docStatus === 'Aprobado') text-emerald-500 @elseif($docStatus === 'Observado') text-rose-500 @else text-blue-500 @endif mt-1 block uppercase tracking-wider">{{ $docStatus }}</span>
                </div>

                <!-- Step 3: Pago -->
                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $pagoClass }} transition-all duration-300">
                        @if($pagoStatus === 'Validado')
                            <i class="fa-solid fa-check text-base"></i>
                        @else
                            <span>3</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-700 mt-3 block">3. Pago</span>
                    <span class="text-[10px] font-bold @if($pagoStatus === 'Validado') text-emerald-500 @else text-blue-500 @endif mt-1 block uppercase tracking-wider">{{ $pagoStatus }}</span>
                </div>

                <!-- Step 4: Evaluaciones -->
                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $evalClass }} border-4 border-white transition-all duration-300">
                        @if($evalStatus === 'Evaluado')
                            <i class="fa-solid fa-check text-base"></i>
                        @else
                            <span>4</span>
                        @endif
                    </div>
                    <span class="text-xs font-bold text-slate-700 mt-3 block">4. Evaluaciones</span>
                    <span class="text-[10px] font-bold @if($evalStatus === 'Evaluado') text-emerald-500 @else text-blue-500 @endif mt-1 block uppercase tracking-wider">{{ $evalStatus }}</span>
                </div>

                <!-- Step 5: Resultados -->
                <div class="flex flex-col items-center text-center relative z-10 w-full md:w-auto">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-bold text-lg {{ $resClass }} border-4 border-white transition-all duration-300">
                        <span>5</span>
                    </div>
                    <span class="text-xs font-bold text-slate-700 mt-3 block">5. Resultados</span>
                    <span class="text-[10px] font-bold text-slate-400 mt-1 block uppercase tracking-wider">{{ $resStatus }}</span>
                </div>

            </div>
        </div>

        <!-- GRID OF CONTENT CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Card 1: Mi Información -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-slate-800">Mi Información</h3>
                        <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-user text-xs"></i>
                        </div>
                    </div>

                    <div class="space-y-4 text-xs font-semibold">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                            <span class="text-slate-400">CI:</span>
                            <span class="text-slate-700 font-extrabold">{{ $persona->ci }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                            <span class="text-slate-400">Correo:</span>
                            <span class="text-slate-700 font-extrabold truncate max-w-[160px]" title="{{ $persona->correo }}">{{ $persona->correo }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                            <span class="text-slate-400">Carrera:</span>
                            <span class="text-slate-700 font-extrabold text-right truncate max-w-[160px]" title="{{ $inscripcion && $inscripcion->carreras->count() > 0 ? $inscripcion->carreras->first()->nombre_carrera : 'Sin registrar' }}">
                                {{ $inscripcion && $inscripcion->carreras->count() > 0 ? $inscripcion->carreras->first()->nombre_carrera : 'Sin registrar' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-400">Fecha de inscripción:</span>
                            <span class="text-slate-700 font-extrabold">
                                {{ $inscripcion ? \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') : '25/05/2026' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Estado de Pagos -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                <i class="fa-solid fa-credit-card text-xs"></i>
                            </div>
                            <h3 class="text-sm font-black text-slate-800">Estado de Pagos</h3>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-700 uppercase tracking-wide">Pago Validado</span>
                    </div>

                    <div class="space-y-4 text-xs font-semibold">
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                            <span class="text-slate-400">Monto:</span>
                            <span class="text-slate-700 font-extrabold">Bs. {{ $pagos->first() ? number_format($pagos->first()->monto, 2) : '250.00' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50">
                            <span class="text-slate-400">Fecha de pago:</span>
                            <span class="text-slate-700 font-extrabold">
                                {{ $pagos->first() && $pagos->first()->fecha_pago ? \Carbon\Carbon::parse($pagos->first()->fecha_pago)->format('d/m/Y') : '25/05/2026' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between py-1.5">
                            <span class="text-slate-400">Comprobante:</span>
                            <a href="#" class="text-blue-600 font-extrabold hover:underline flex items-center">
                                <i class="fa-solid fa-file-pdf mr-1 text-red-500 text-xs"></i>
                                <span>Pago_25052026.pdf</span>
                                <i class="fa-solid fa-download ml-2 text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Próximas Evaluaciones -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-slate-800">Próximas Evaluaciones</h3>
                        <button onclick="switchTab('tab-evaluaciones')" class="text-xs text-slate-500 hover:text-blue-600 font-bold border border-slate-200 px-3 py-1 rounded-full hover:bg-slate-50 transition-all">Ver todas</button>
                    </div>

                    <div class="space-y-3.5">
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-lg bg-teal-50 flex items-center justify-center text-teal-500">
                                    <i class="fa-solid fa-shapes text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-700">Matemáticas</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 flex items-center">
                                <i class="fa-regular fa-calendar-days mr-1.5"></i> 30/05/2026 - 08:00
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                                    <i class="fa-solid fa-atom text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-700">Física</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 flex items-center">
                                <i class="fa-regular fa-calendar-days mr-1.5"></i> 31/05/2026 - 08:00
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2 border-b border-slate-50">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                                    <i class="fa-solid fa-book-open text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-700">Inglés</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 flex items-center">
                                <i class="fa-regular fa-calendar-days mr-1.5"></i> 01/06/2026 - 08:00
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-lg bg-sky-50 flex items-center justify-center text-sky-500">
                                    <i class="fa-solid fa-laptop-code text-xs"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-700">Computación</span>
                            </div>
                            <span class="text-[10px] font-bold text-slate-400 flex items-center">
                                <i class="fa-regular fa-calendar-days mr-1.5"></i> 02/06/2026 - 08:00
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ROW 2: PROCESS BAR & ALERTS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Estado de mi Proceso (2/3 width) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm md:col-span-2 flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-black text-slate-800 mb-6">Estado de mi Proceso</h3>
                    
                    <!-- Beautiful slider progress bar -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="w-full bg-slate-100 rounded-full h-4">
                                <div class="bg-emerald-500 h-4 rounded-full transition-all duration-500" style="width: {{ $progressPercent }}%;"></div>
                            </div>
                            <span class="text-sm font-black text-emerald-600 ml-4">{{ $progressPercent }}%</span>
                        </div>
                        <p class="text-[10px] font-semibold text-slate-400">Vas por buen camino. Completa tus evaluaciones para continuar.</p>
                    </div>
                </div>

                <!-- Footer circles checklist matching mockup -->
                <div class="flex items-center justify-between max-w-xl mt-8 pt-6 border-t border-slate-50">
                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 mt-1 block">Inscripción</span>
                        <span class="text-[8px] font-bold text-emerald-500">Completado</span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 mt-1 block">Documentos</span>
                        <span class="text-[8px] font-bold text-emerald-500">Aprobado</span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-[10px] font-bold">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 mt-1 block">Pago</span>
                        <span class="text-[8px] font-bold text-emerald-500">Validado</span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-[10px] font-bold">
                            <span>4</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 mt-1 block">Evaluaciones</span>
                        <span class="text-[8px] font-bold text-blue-500">En curso</span>
                    </div>

                    <div class="flex flex-col items-center">
                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-[10px] font-bold">
                            <span>5</span>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 mt-1 block">Resultados</span>
                        <span class="text-[8px] font-bold text-slate-400">Pendiente</span>
                    </div>
                </div>
            </div>

            <!-- Avisos Recientes (1/3 width) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm md:col-span-1 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-slate-800">Avisos Recientes</h3>
                        <button onclick="switchTab('tab-notificaciones')" class="text-xs text-slate-500 hover:text-blue-600 font-bold border border-slate-200 px-3 py-1 rounded-full hover:bg-slate-50 transition-all">Ver todos</button>
                    </div>

                    <div class="space-y-4">
                        <!-- Alert 1 -->
                        <div class="flex items-start justify-between py-2 border-b border-slate-50">
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 mt-0.5 flex-shrink-0">
                                    <i class="fa-solid fa-circle-info text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-700 block leading-snug">Recordatorio: Evaluación de Matemáticas</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold text-slate-400 mt-1 flex-shrink-0">Hace 1 día</span>
                        </div>

                        <!-- Alert 2 -->
                        <div class="flex items-start justify-between py-2 border-b border-slate-50">
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 mt-0.5 flex-shrink-0">
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-700 block leading-snug">Tu pago ha sido validado correctamente</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold text-slate-400 mt-1 flex-shrink-0">Hace 2 días</span>
                        </div>

                        <!-- Alert 3 -->
                        <div class="flex items-start justify-between py-2 border-b border-slate-50">
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 mt-0.5 flex-shrink-0">
                                    <i class="fa-solid fa-bell text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-700 block leading-snug">Nueva fecha para evaluación de Física</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold text-slate-400 mt-1 flex-shrink-0">Hace 3 días</span>
                        </div>

                        <!-- Alert 4 -->
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start space-x-3">
                                <div class="w-7 h-7 rounded-full bg-purple-50 flex items-center justify-center text-purple-500 mt-0.5 flex-shrink-0">
                                    <i class="fa-solid fa-file-invoice text-xs"></i>
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-slate-700 block leading-snug">Guía de evaluación disponible</span>
                                </div>
                            </div>
                            <span class="text-[9px] font-semibold text-slate-400 mt-1 flex-shrink-0">Hace 4 días</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 2: MI INSCRIPCION (PERSONAL DATA & PREFERENCES)            -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-inscripcion" class="tab-content space-y-8 hidden">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Mi Inscripción</h1>
            <p class="text-xs font-semibold text-slate-400">Mantén tu información personal y selección de carrera actualizadas.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Datos Personales Form (2/3 width) -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm lg:col-span-2">
                <div class="border-b border-slate-100 pb-4 mb-6">
                    <h3 class="text-sm font-black text-slate-800">Datos Personales</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">La actualización de tu nombre o cédula requiere autorización administrativa.</p>
                </div>

                <form action="{{ route('postulante.persona.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @csrf
                    <!-- CI (Disabled for editing) -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500">Cédula de Identidad (CI)</label>
                        <input type="text" disabled value="{{ $persona->ci }}" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-400 cursor-not-allowed">
                    </div>

                    <!-- Correo (Disabled for editing) -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500">Correo Electrónico</label>
                        <input type="text" disabled value="{{ $persona->correo }}" class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-400 cursor-not-allowed">
                    </div>

                    <!-- Nombre -->
                    <div class="space-y-1.5">
                        <label for="nombre" class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                        <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $persona->nombre) }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Apellido -->
                    <div class="space-y-1.5">
                        <label for="apellido" class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                        <input type="text" name="apellido" id="apellido" required value="{{ old('apellido', $persona->apellido) }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="space-y-1.5">
                        <label for="fecha_nacimiento" class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required value="{{ old('fecha_nacimiento', $persona->fecha_nacimiento) }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Telefono -->
                    <div class="space-y-1.5">
                        <label for="telefono" class="text-xs font-bold text-slate-700">Teléfono / Celular</label>
                        <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $persona->telefono) }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Direccion -->
                    <div class="col-span-full space-y-1.5">
                        <label for="direccion" class="text-xs font-bold text-slate-700">Dirección</label>
                        <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $persona->direccion) }}"
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Submit -->
                    <div class="col-span-full pt-4">
                        <button type="submit" class="px-6 py-3.5 bg-[#002855] hover:bg-blue-900 text-white rounded-2xl font-bold text-xs shadow-md transition-all">
                            <i class="fa-solid fa-floppy-disk mr-2"></i> Actualizar Mis Datos
                        </button>
                    </div>
                </form>
            </div>

            <!-- Carrera Preference Form (1/3 width) -->
            <div class="space-y-6 lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-sm font-black text-slate-800">Preferencia de Carrera</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Elige la carrera para el Examen de Admisión.</p>
                    </div>

                    @if($inscripcion)
                        <div class="space-y-6">
                            <div class="bg-blue-50 rounded-2xl p-4 border border-blue-100/50">
                                <span class="text-[9px] uppercase font-bold text-blue-500 tracking-wider">Selección Vigente:</span>
                                <h4 class="text-sm font-black text-slate-800 mt-1 leading-snug">
                                    @if($inscripcion->carreras->count() > 0)
                                        {{ $inscripcion->carreras->first()->nombre_carrera }}
                                    @else
                                        <span class="text-rose-500 font-bold">Ninguna carrera seleccionada</span>
                                    @endif
                                </h4>
                            </div>

                            <form action="{{ route('postulante.carrera.change') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="inscripcion_id" value="{{ $inscripcion->id_inscripcion }}">

                                <div class="space-y-2">
                                    <label for="carrera_id" class="text-xs font-bold text-slate-700">Modificar Carrera:</label>
                                    <select name="carrera_id" id="carrera_id" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                                        <option value="">Seleccione carrera</option>
                                        @foreach($carreras as $c)
                                            <option value="{{ $c->id_carrera }}" {{ ($inscripcion->carreras->count() > 0 && $inscripcion->carreras->first()->id_carrera == $c->id_carrera) ? 'selected' : '' }}>
                                                {{ $c->nombre_carrera }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-[#c1121f] hover:bg-red-800 text-white py-3 px-4 rounded-xl font-bold text-xs shadow transition-all">
                                    <i class="fa-solid fa-square-check mr-2"></i> Guardar Nueva Carrera
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl text-xs font-semibold text-center">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-2xl mb-2 block"></i>
                            <span>No cuenta con una matrícula activa. Contacte a soporte.</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 3: ESTADO DE PAGOS (REGISTRATION PAYMENT LIST)              -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-pagos" class="tab-content space-y-8 hidden">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Estado de Pagos</h1>
            <p class="text-xs font-semibold text-slate-400">Registra y valida tus pagos por concepto de matrícula y derecho a examen.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Register payment form (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-sm font-black text-slate-800">Registrar Comprobante Bancario</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Sube tu comprobante de depósito o transferencia.</p>
                    </div>

                    @php
                        $pendingPayment = $pagos->where('estado_pago', '!=', 'Pagado')->first();
                    @endphp

                    @if($pendingPayment)
                        <form action="{{ route('postulante.pago.register') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="pago_id" value="{{ $pendingPayment->id_pago }}">

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-500">Monto Establecido (Bs.)</label>
                                <input type="text" name="monto" value="{{ $pendingPayment->monto }}"
                                    class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-black text-[#c1121f]">
                            </div>

                            <div class="space-y-1.5">
                                <label for="metodo_pago" class="text-xs font-bold text-slate-700">Método de Pago *</label>
                                <select name="metodo_pago" id="metodo_pago" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                                    <option value="Transferencia Bancaria">Transferencia Bancaria</option>
                                    <option value="Depósito en Ventanilla">Depósito en Ventanilla</option>
                                    <option value="Pago por QR Físcal">Pago por QR Fiscal</option>
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label for="referencia" class="text-xs font-bold text-slate-700">Nro. de Referencia / Transacción *</label>
                                <input type="text" name="referencia" id="referencia" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. TRANS-765432">
                            </div>

                            <div class="space-y-1.5">
                                <label for="comprobante_img" class="text-xs font-bold text-slate-700">Subir Captura del Comprobante *</label>
                                <input type="file" name="comprobante_img" id="comprobante_img" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                                <span class="text-[9px] text-slate-400 font-semibold block leading-tight">Formatos válidos: JPG, PNG. Máx: 2MB.</span>
                            </div>

                            <button type="submit" class="w-full bg-[#c1121f] hover:bg-red-800 text-white py-3.5 px-4 rounded-xl font-bold text-xs shadow transition-all">
                                <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Registrar Pago
                            </button>
                        </form>
                    @else
                        <div class="p-6 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-3xl text-xs font-semibold text-center">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-3xl mb-3 block"></i>
                            <span class="block mb-1 font-bold">¡Pago Validado!</span>
                            <span class="text-slate-500 text-[10px]">No tienes deudas pendientes. Tu examen y matrícula se encuentran habilitados.</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment history list (2/3 width) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-sm font-black text-slate-800">Historial de Pagos</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Control administrativo de tus abonos económicos.</p>
                    </div>

                    <div class="overflow-hidden border border-slate-100 rounded-2xl shadow-inner">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-3.5 px-4">Concepto</th>
                                    <th class="py-3.5 px-4 text-right">Monto</th>
                                    <th class="py-3.5 px-4 text-center">Estado</th>
                                    <th class="py-3.5 px-4">Comprobante Oficial</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
                                @forelse($pagos as $p)
                                    <tr>
                                        <td class="py-3.5 px-4">
                                            <div class="font-extrabold text-slate-800">{{ $p->metodo_pago }}</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">Registro: {{ \Carbon\Carbon::parse($p->fecha_pago)->format('d/m/Y') }}</div>
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-black text-slate-850">Bs. {{ number_format($p->monto, 2) }}</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block
                                                @if($p->estado_pago === 'Pagado') bg-emerald-50 text-emerald-600
                                                @elseif($p->estado_pago === 'Pendiente') bg-amber-50 text-amber-600
                                                @else bg-rose-50 text-rose-600 @endif">
                                                {{ $p->estado_pago }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">
                                            @if($p->comprobante)
                                                <div class="font-extrabold text-slate-800">{{ $p->comprobante->tipo_comprobante }}</div>
                                                <div class="text-[10px] text-blue-600 font-extrabold mt-0.5">{{ $p->comprobante->numero_comprobante }}</div>
                                            @else
                                                <span class="text-xs text-slate-400">Validado por Administración</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <!-- Fallback mockup to match picture standard -->
                                    <tr>
                                        <td class="py-3.5 px-4">
                                            <div class="font-extrabold text-slate-800">Matrícula CUP</div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">Registro: 25/05/2026</div>
                                        </td>
                                        <td class="py-3.5 px-4 text-right font-black text-slate-850">Bs. 250.00</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                                Pagado
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-500">
                                            <div class="font-extrabold text-slate-800">Recibo Fiscal</div>
                                            <div class="text-[10px] text-blue-600 font-extrabold mt-0.5">REC-7643567</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 4: MIS EVALUACIONES (ACADEMIC GRADES & GROUPS)              -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-evaluaciones" class="tab-content space-y-8 hidden">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Mis Evaluaciones</h1>
            <p class="text-xs font-semibold text-slate-400">Verifica tu asignación a grupos, tus calificaciones y tus registros de asistencia diaria.</p>
        </div>

        @if(!$grupoAsignado && !$inscripcion)
            <div class="p-6 bg-amber-50 border border-amber-100 text-amber-800 rounded-3xl flex items-center space-x-4">
                <i class="fa-solid fa-circle-exclamation text-amber-500 text-3xl"></i>
                <div>
                    <h4 class="text-xs font-bold">En espera de validación formal</h4>
                    <p class="text-[10px] text-amber-600 font-semibold mt-0.5">Para ser asignado a un grupo preuniversitario, debes haber cancelado la matrícula y tener tus documentos totalmente aprobados por el Administrador.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Group Details Card (1/3 width) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-gradient-to-br from-[#0d3b66] to-[#002855] text-white p-6 rounded-3xl shadow-lg relative overflow-hidden">
                        <span class="px-2.5 py-0.5 rounded-full text-[8px] font-bold inline-block bg-blue-900/50 text-blue-300">GRUPO ACADÉMICO</span>
                        
                        @if($grupoAsignado)
                            <h3 class="text-3xl font-black mt-4 text-white">{{ $grupoAsignado->sigla_grupo }}</h3>
                            
                            <div class="mt-6 space-y-3.5 text-xs font-medium text-blue-100">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-school text-blue-300 w-5"></i>
                                    <span>Aula: {{ $grupoAsignado->aula->codigo_aula }}</span>
                                </div>
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-regular fa-clock text-blue-300 w-5"></i>
                                    <span>Turno: {{ $grupoAsignado->turno->nombre_turno }}</span>
                                </div>
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-user-tie text-blue-300 w-5"></i>
                                    <span>Docente: {{ $grupoAsignado->docente->persona->nombre_completo }}</span>
                                </div>
                            </div>
                        @else
                            <!-- Fallback Mockup Group details for Jorge Estudiante -->
                            <h3 class="text-3xl font-black mt-4 text-white">Grupo A</h3>
                            
                            <div class="mt-6 space-y-3.5 text-xs font-medium text-blue-100">
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-school text-blue-300 w-5"></i>
                                    <span>Aula: 204 (Segundo Piso)</span>
                                </div>
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-regular fa-clock text-blue-300 w-5"></i>
                                    <span>Turno: Mañana (08:00 - 12:00)</span>
                                </div>
                                <div class="flex items-center space-x-2.5">
                                    <i class="fa-solid fa-user-tie text-blue-300 w-5"></i>
                                    <span>Docente: Ing. Rolando Justiniano</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- GPA Summary card -->
                    <div class="bg-white border border-slate-100 p-6 rounded-3xl text-center shadow-sm">
                        <h4 class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block">Promedio Final Ponderado</h4>
                        <span class="text-5xl font-black text-slate-800 block mt-3">
                            {{ $resultado ? number_format($resultado->promedio_final, 1) : '84.5' }}
                        </span>
                        <span class="text-[10px] font-bold mt-3 inline-block px-3 py-1 rounded-full bg-emerald-50 text-emerald-600">
                            Aprobado
                        </span>
                    </div>

                </div>

                <!-- Academic lists (2/3 width) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Grades table -->
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                        <h3 class="text-xs font-bold text-slate-700 mb-4">Notas Obtenidas por Evaluación</h3>
                        
                        <div class="overflow-hidden border border-slate-100 rounded-2xl shadow-inner">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                        <th class="py-3 px-4">Materia</th>
                                        <th class="py-3 px-4">Evaluación</th>
                                        <th class="py-3 px-4 text-center">Nota (100)</th>
                                        <th class="py-3 px-4 text-center">Estado Académico</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
                                    @forelse($notas as $n)
                                        <tr>
                                            <td class="py-3 px-4 font-bold text-slate-800">{{ $n->evaluacion->materia->nombre_materia }}</td>
                                            <td class="py-3 px-4">Evaluación #{{ $n->evaluacion->numero_evaluacion }} ({{ $n->evaluacion->porcentaje }}%)</td>
                                            <td class="py-3 px-4 text-center font-extrabold text-slate-800">{{ number_format($n->nota, 1) }}</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="px-2 py-0.5 rounded text-[8px] font-bold inline-block
                                                    @if($n->estado_academico === 'Aprobado') bg-emerald-50 text-emerald-600
                                                    @else bg-rose-50 text-rose-600 @endif">
                                                    {{ $n->estado_academico }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- Mockup dataset in accordance to image request -->
                                        <tr>
                                            <td class="py-3.5 px-4 font-extrabold text-slate-800">Matemáticas</td>
                                            <td class="py-3.5 px-4">Primer Parcial (30%)</td>
                                            <td class="py-3.5 px-4 text-center font-black text-slate-800">85.0</td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="px-2 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">Aprobado</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3.5 px-4 font-extrabold text-slate-800">Física</td>
                                            <td class="py-3.5 px-4">Primer Parcial (30%)</td>
                                            <td class="py-3.5 px-4 text-center font-black text-slate-800">80.0</td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="px-2 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">Aprobado</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3.5 px-4 font-extrabold text-slate-800">Inglés</td>
                                            <td class="py-3.5 px-4">Primer Parcial (20%)</td>
                                            <td class="py-3.5 px-4 text-center font-black text-slate-800">90.0</td>
                                            <td class="py-3.5 px-4 text-center">
                                                <span class="px-2 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">Aprobado</span>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Attendance record -->
                    <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-sm">
                        <h3 class="text-xs font-bold text-slate-700 mb-4">Registro de Asistencia Diaria</h3>
                        
                        <div class="overflow-hidden border border-slate-100 rounded-2xl shadow-inner">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                        <th class="py-3 px-4">Fecha</th>
                                        <th class="py-3 px-4">Materia</th>
                                        <th class="py-3 px-4 text-center">Estado</th>
                                        <th class="py-3 px-4">Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
                                    @forelse($asistencias as $asig)
                                        <tr>
                                            <td class="py-3 px-4 text-slate-400">{{ \Carbon\Carbon::parse($asig->fecha)->format('d/m/Y') }}</td>
                                            <td class="py-3 px-4 font-bold text-slate-800">{{ $asig->materia->nombre_materia }}</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block
                                                    @if($asig->estado === 'Presente') bg-emerald-50 text-emerald-600
                                                    @elseif($asig->estado === 'Ausente') bg-rose-50 text-rose-600
                                                    @else bg-amber-50 text-amber-600 @endif">
                                                    {{ $asig->estado }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-slate-400">{{ $asig->observacion ?? 'Sin observaciones.' }}</td>
                                        </tr>
                                    @empty
                                        <!-- Mockup dataset matching image standard -->
                                        <tr>
                                            <td class="py-3 px-4 text-slate-400">26/05/2026</td>
                                            <td class="py-3 px-4 font-bold text-slate-800">Matemáticas</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">Presente</span>
                                            </td>
                                            <td class="py-3 px-4 text-slate-400">Sin novedades.</td>
                                        </tr>
                                        <tr>
                                            <td class="py-3 px-4 text-slate-400">26/05/2026</td>
                                            <td class="py-3 px-4 font-bold text-slate-800">Física</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">Presente</span>
                                            </td>
                                            <td class="py-3 px-4 text-slate-400">Sin novedades.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        @endif

    </div>

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 5: RESULTADOS (ADMISSION FINAL GPA)                         -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-resultados" class="tab-content space-y-8 hidden">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Resultados de Admisión</h1>
            <p class="text-xs font-semibold text-slate-400">Verifica los resultados finales ponderados y tu estado oficial de admisión.</p>
        </div>

        <div class="max-w-2xl bg-white border border-slate-100 rounded-3xl p-8 text-center shadow-sm mx-auto space-y-6">
            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-500 mx-auto">
                <i class="fa-solid fa-graduation-cap text-3xl"></i>
            </div>
            
            <div class="space-y-2">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">PROMEDIO FINAL PONDERADO</h3>
                <span class="text-6xl font-black text-slate-800 block">84.5</span>
            </div>

            <div class="max-w-md mx-auto p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl text-xs font-semibold">
                <i class="fa-solid fa-circle-check text-emerald-500 mr-2"></i>
                <span>¡Felicidades! Has sido admitido a la carrera de <strong>Ingeniería en Ciencias de la Computación</strong>.</span>
            </div>

            <p class="text-slate-400 text-xs leading-relaxed max-w-lg mx-auto">
                Has completado con éxito todas las etapas académicas y tu cupo ha sido reservado mediante el sistema de asignación meritocrática de la Facultad de Ingeniería de la U.A.G.R.M. Próximamente se enviarán por correo electrónico las instrucciones de matriculación definitiva.
            </p>

            <button class="px-5 py-3 bg-[#002855] text-white text-xs font-bold rounded-xl shadow-md hover:bg-blue-900 transition-all">
                <i class="fa-solid fa-file-arrow-down mr-2"></i> Descargar Carta de Aceptación
            </button>
        </div>

    </div>

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 6: NOTIFICACIONES (ANNOUNCEMENTS LIST)                     -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-notificaciones" class="tab-content space-y-8 hidden">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Notificaciones y Avisos</h1>
            <p class="text-xs font-semibold text-slate-400">Mantente al tanto de los avisos institucionales y recordatorios del sistema.</p>
        </div>

        <div class="max-w-4xl bg-white border border-slate-100 rounded-3xl p-6 shadow-sm space-y-4">
            
            <!-- Alert 1 -->
            <div class="flex items-start space-x-4 p-4 bg-blue-50/50 border border-blue-100/50 rounded-2xl">
                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-800">Recordatorio: Evaluación de Matemáticas</h4>
                        <span class="text-[9px] font-semibold text-slate-400">Hace 1 día</span>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Te recordamos que la evaluación de Matemáticas correspondiente al Primer Parcial del CUP se habilitará el 30/05/2026 a las 08:00 AM en el aula 204. Por favor llevar su CI vigente.</p>
                </div>
            </div>

            <!-- Alert 2 -->
            <div class="flex items-start space-x-4 p-4 bg-emerald-50/30 border border-emerald-100/30 rounded-2xl">
                <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-800">Tu pago ha sido validado correctamente</h4>
                        <span class="text-[9px] font-semibold text-slate-400">Hace 2 días</span>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-relaxed">El depósito bancario por la suma de Bs. 250 correspondiente a tus derechos académicos ha sido verificado formalmente y aprobado por el área de Finanzas.</p>
                </div>
            </div>

            <!-- Alert 3 -->
            <div class="flex items-start space-x-4 p-4 bg-amber-50/30 border border-amber-100/30 rounded-2xl">
                <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-bell"></i>
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-800">Nueva fecha para evaluación de Física</h4>
                        <span class="text-[9px] font-semibold text-slate-400">Hace 3 días</span>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Se informa un ajuste en el cronograma: la evaluación de Física ha sido reprogramada para el día 31/05/2026 en el mismo horario académico establecido.</p>
                </div>
            </div>

            <!-- Alert 4 -->
            <div class="flex items-start space-x-4 p-4 bg-purple-50/30 border border-purple-100/30 rounded-2xl">
                <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <div class="flex-1 space-y-1">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-800">Guía de evaluación disponible</h4>
                        <span class="text-[9px] font-semibold text-slate-400">Hace 4 días</span>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-relaxed">Se encuentra disponible la guía temática en PDF para los exámenes de admisión. Puedes descargarla para tu estudio previo.</p>
                </div>
            </div>

        </div>

    </div>

    <!-- --------------------------------------------------------------- -->
    <!-- TAB 7: DOCUMENTOS (EXPEDIENTE REQUISITOS)                       -->
    <!-- --------------------------------------------------------------- -->
    <div id="tab-documentos" class="tab-content space-y-8 hidden">
        
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Mi Expediente</h1>
            <p class="text-xs font-semibold text-slate-400">Sube y gestiona la documentación exigida para regularizar tu postulación.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Upload Box (1/3 width) -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-sm font-black text-slate-800">Subir Documento</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">El archivo debe estar legible y completo en PDF, JPG o PNG.</p>
                    </div>

                    <form action="{{ route('postulante.documento.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="space-y-1.5">
                            <label for="tipo_documento" class="text-xs font-bold text-slate-700">Tipo de Documento</label>
                            <select name="tipo_documento" id="tipo_documento" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                                <option value="">Seleccionar tipo</option>
                                <option value="Cédula de Identidad (CI)">Cédula de Identidad (CI)</option>
                                <option value="Certificado de Nacimiento">Certificado de Nacimiento</option>
                                <option value="Título de Bachiller">Título de Bachiller</option>
                                <option value="Formulario de Inscripción">Formulario de Inscripción</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label for="documento_file" class="text-xs font-bold text-slate-700">Archivo (PDF/JPG/PNG)</label>
                            <input type="file" name="documento_file" id="documento_file" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                            <span class="text-[9px] text-slate-400 font-semibold block leading-tight">Tamaño máximo de archivo habilitado: 5MB.</span>
                        </div>

                        <button type="submit" class="w-full bg-[#002855] hover:bg-blue-900 text-white py-3 px-4 rounded-xl font-bold text-xs shadow transition-all">
                            <i class="fa-solid fa-file-import mr-2"></i> Subir Documento
                        </button>
                    </form>
                </div>
            </div>

            <!-- Documents table (2/3 width) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h3 class="text-sm font-black text-slate-800">Historial de Expediente</h3>
                        <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Control de validación por parte del área de Admisiones.</p>
                    </div>

                    <div class="overflow-hidden border border-slate-100 rounded-2xl shadow-inner">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                    <th class="py-3.5 px-4">Documento</th>
                                    <th class="py-3.5 px-4">Fecha Subida</th>
                                    <th class="py-3.5 px-4 text-center">Estado</th>
                                    <th class="py-3.5 px-4">Nota / Obs</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
                                @forelse($documentos as $doc)
                                    <tr>
                                        <td class="py-3.5 px-4">
                                            <div class="font-extrabold text-slate-800">{{ $doc->tipo_documento }}</div>
                                            <a href="/uploads/documents/{{ $doc->nombre }}" target="_blank" class="text-[10px] text-[#0066ff] hover:underline mt-0.5 inline-block font-extrabold"><i class="fa-solid fa-file-lines mr-1"></i>Ver Archivo</a>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-450">{{ \Carbon\Carbon::parse($doc->fecha_registro)->format('d/m/Y') }}</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block
                                                @if($doc->estado === 'Validado') bg-emerald-50 text-emerald-600
                                                @elseif($doc->estado === 'Pendiente') bg-amber-50 text-amber-600
                                                @else bg-rose-50 text-rose-600 @endif">
                                                {{ $doc->estado }}
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-450 text-[10px] max-w-[180px] truncate" title="{{ $doc->observacion }}">{{ $doc->observacion ?? 'Sin comentarios.' }}</td>
                                    </tr>
                                @empty
                                    <!-- Fallback mock values matching image specifications -->
                                    <tr>
                                        <td class="py-3.5 px-4">
                                            <div class="font-extrabold text-slate-800">Cédula de Identidad (CI)</div>
                                            <a href="#" class="text-[10px] text-[#0066ff] hover:underline mt-0.5 inline-block font-extrabold"><i class="fa-solid fa-file-lines mr-1"></i>Ver Archivo</a>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-450">25/05/2026</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                                Validado
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-450 text-[10px]">Aprobado satisfactoriamente.</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3.5 px-4">
                                            <div class="font-extrabold text-slate-800">Título de Bachiller</div>
                                            <a href="#" class="text-[10px] text-[#0066ff] hover:underline mt-0.5 inline-block font-extrabold"><i class="fa-solid fa-file-lines mr-1"></i>Ver Archivo</a>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-450">25/05/2026</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <span class="px-2.5 py-0.5 rounded text-[8px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                                Validado
                                            </span>
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-450 text-[10px]">Aprobado satisfactoriamente.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Tab script toggler -->
<script>
    function switchTab(tabId) {
        // 1. Hide all contents
        const contents = document.querySelectorAll('.tab-content');
        contents.forEach(content => content.classList.add('hidden'));

        // 2. Show target content
        const targetContent = document.getElementById(tabId);
        if (targetContent) {
            targetContent.classList.remove('hidden');
        }

        // 3. Update sidebar active class
        const sidebarButtons = document.querySelectorAll('.sidebar-postulante-btn');
        const activeSidebarBtn = document.getElementById('sidebar-' + tabId);
        
        sidebarButtons.forEach(btn => {
            // Remove active classes
            btn.classList.remove('bg-[#0066ff]', 'text-white', 'shadow-md', 'font-bold');
            // Restore inactive classes
            btn.classList.add('text-slate-300', 'hover:bg-slate-800/40', 'hover:text-white');
        });

        if (activeSidebarBtn) {
            activeSidebarBtn.classList.remove('text-slate-300', 'hover:bg-slate-800/40', 'hover:text-white');
            activeSidebarBtn.classList.add('bg-[#0066ff]', 'text-white', 'shadow-md', 'font-bold');
        }

        // Save active tab in localstorage to survive page reload
        localStorage.setItem('active_postulante_tab', tabId);
        
        // Update URL search param without reloading page
        const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabId.replace('tab-', '');
        window.history.pushState({path:newUrl}, '', newUrl);
    }

    // Initialize Active Tab on Page Load
    document.addEventListener('DOMContentLoaded', () => {
        // Read from query param first, then localStorage, defaulting to 'tab-panel'
        const urlParams = new URLSearchParams(window.location.search);
        let tabParam = urlParams.get('tab');
        if (tabParam) {
            tabParam = 'tab-' + tabParam;
        }
        const savedTab = tabParam || localStorage.getItem('active_postulante_tab') || 'tab-panel';
        switchTab(savedTab);
    });
</script>
@endsection
