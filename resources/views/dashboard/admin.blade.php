@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    
    <!-- Welcome Header & Time -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Panel Administrativo</h1>
            <p class="text-xs text-slate-400 font-semibold">Resumen general y control de la gestión académica y operativa del examen de admisión CUP.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2.5 bg-white border border-slate-100 rounded-2xl text-xs font-bold text-slate-600 shadow-sm flex items-center">
                <i class="fa-regular fa-calendar-days mr-2 text-slate-400"></i>
                {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
            </span>
            <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-extrabold rounded-full border border-blue-100 flex items-center">
                <span class="w-2 h-2 rounded-full bg-blue-500 mr-1.5 animate-pulse"></span>
                Administrador
            </span>
        </div>
    </div>

    <!-- KPIs row matching premium aesthetic -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Metric 1: Postulantes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="z-10">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ number_format($kpis['postulantes']) }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Postulantes</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Registrados totales</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="{{ route('admin.documentos') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Metric 2: Docentes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="z-10">
                <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ $kpis['docentes'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Docentes</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Asignados activos</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="{{ route('admin.usuarios') }}?tab=docentes" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Metric 3: Grupos -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-people-group"></i>
            </div>
            <div class="z-10">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ $kpis['grupos'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Grupos</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Cursos académicos</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="{{ route('admin.grupos') }}" class="text-[10px] font-bold text-amber-600 hover:text-amber-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Metric 4: Recaudado -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-credit-card"></i>
            </div>
            <div class="z-10">
                <div class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">Bs. {{ number_format($kpis['recaudado'], 2) }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Recaudado</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Pagos validados</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="{{ route('admin.pagos') }}" class="text-[10px] font-bold text-purple-600 hover:text-purple-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Metric 5: Pendientes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="z-10">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ $kpis['pagos_pendientes'] + $kpis['documentos_pendientes'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Pendientes</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Trámites por revisar</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="#" class="text-[10px] font-bold text-rose-600 hover:text-rose-800 flex items-center space-x-1">
                    <span>Atender trámites</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Review Alerts / Action Items -->
    @if($kpis['pagos_pendientes'] > 0 || $kpis['documentos_pendientes'] > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($kpis['documentos_pendientes'] > 0)
                <div class="bg-amber-50/50 border border-amber-100 p-5 rounded-3xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-2xl bg-amber-100/70 text-amber-600 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-amber-900 leading-tight">Revisión de Expedientes</h4>
                            <p class="text-[10px] text-amber-600 font-semibold mt-1">Tienes {{ $kpis['documentos_pendientes'] }} documentos de postulantes pendientes de validación.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.documentos') }}" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-[10px] font-bold shadow-md hover:shadow transition-all flex-shrink-0">Revisar</a>
                </div>
            @endif

            @if($kpis['pagos_pendientes'] > 0)
                <div class="bg-blue-50/50 border border-blue-100 p-5 rounded-3xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 rounded-2xl bg-blue-100/70 text-blue-600 flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-blue-900 leading-tight">Validación de Transacciones</h4>
                            <p class="text-[10px] text-blue-600 font-semibold mt-1">Tienes {{ $kpis['pagos_pendientes'] }} comprobantes de pago pendientes de verificación.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.pagos') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-bold shadow-md hover:shadow transition-all flex-shrink-0">Validar</a>
                </div>
            @endif
        </div>
    @endif

    <!-- Process Admission & Career Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Proceso de Admisión - datos reales (2/3 width) -->
        @php
            $totalPost = $kpis['postulantes'] ?: 1;
            $docAprobados = $stats_admision['docs_aprobados'] ?? 0;
            $pagosVal     = $kpis['recaudado'] > 0 ? ($totalPost - $kpis['pagos_pendientes']) : 0;
            $admitidos    = $stats_admision['admitidos'] ?? 0;
            $pctDocs  = $totalPost > 0 ? round($docAprobados / $totalPost * 100) : 0;
            $pctPagos = $totalPost > 0 ? min(100, round(($totalPost - $kpis['pagos_pendientes']) / $totalPost * 100)) : 0;
            $pctAdmit = $totalPost > 0 ? round($admitidos / $totalPost * 100) : 0;
        @endphp
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 lg:col-span-2 flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h3 class="text-sm font-black text-slate-850">Proceso de Admisión — Estado Actual</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Progreso secuencial del registro y admisión del examen CUP</p>
            </div>

            <div class="space-y-5 flex-1 flex flex-col justify-center">
                <!-- Postulantes Registrados -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                        <span class="flex items-center"><i class="fa-solid fa-users text-blue-500 w-5"></i> Postulantes Registrados</span>
                        <span class="font-black text-slate-800">{{ number_format($kpis['postulantes']) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-blue-600 h-3 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <!-- Documentación Aprobada -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                        <span class="flex items-center"><i class="fa-solid fa-file-circle-check text-emerald-500 w-5"></i> Documentación Aprobada</span>
                        <span class="font-black text-slate-800">{{ number_format($docAprobados) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-emerald-500 h-3 rounded-full" style="width: {{ $pctDocs }}%"></div>
                    </div>
                </div>

                <!-- Pagos Validados -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                        <span class="flex items-center"><i class="fa-solid fa-credit-card text-amber-500 w-5"></i> Pagos Validados</span>
                        <span class="font-black text-slate-800">{{ number_format($stats_admision['pagos_validados'] ?? 0) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-amber-500 h-3 rounded-full" style="width: {{ $pctPagos }}%"></div>
                    </div>
                </div>

                <!-- Pendientes (docs + pagos) -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                        <span class="flex items-center"><i class="fa-solid fa-hourglass-half text-purple-500 w-5"></i> Trámites Pendientes</span>
                        <span class="font-black text-slate-800">{{ number_format($kpis['pagos_pendientes'] + $kpis['documentos_pendientes']) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        @php $pctPend = $totalPost > 0 ? min(100, round(($kpis['pagos_pendientes'] + $kpis['documentos_pendientes']) / $totalPost * 100)) : 0; @endphp
                        <div class="bg-purple-500 h-3 rounded-full" style="width: {{ $pctPend }}%"></div>
                    </div>
                </div>

                <!-- Admitidos -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center text-xs font-bold text-slate-700">
                        <span class="flex items-center"><i class="fa-solid fa-award text-cyan-500 w-5"></i> Postulantes Admitidos</span>
                        <span class="font-black text-slate-800">{{ number_format($admitidos) }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                        <div class="bg-cyan-500 h-3 rounded-full" style="width: {{ $pctAdmit }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Postulantes por Carrera — datos reales (1/3 width) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-4 mb-4">
                <h3 class="text-sm font-bold text-slate-800">Postulantes por Carrera</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Distribución por programa académico</p>
            </div>

            <div class="flex flex-col items-center justify-center py-2 flex-1">
                @php
                    $colors = ['text-blue-600','text-cyan-400','text-amber-500','text-purple-500','text-rose-400','text-emerald-500'];
                    $bgColors = ['bg-blue-600','bg-cyan-400','bg-amber-500','bg-purple-500','bg-rose-400','bg-emerald-500'];
                    $offset = 0;
                @endphp

                @if($postulantes_carrera->count() > 0)
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-slate-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        @foreach($postulantes_carrera as $idx => $pc)
                            @php
                                $pct = $kpis['postulantes'] > 0 ? round($pc->total / $kpis['postulantes'] * 100) : 0;
                                $colorClass = $colors[$idx % count($colors)];
                            @endphp
                            <path class="{{ $colorClass }}" stroke-dasharray="{{ $pct }}, 100" stroke-dashoffset="-{{ $offset }}" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            @php $offset += $pct; @endphp
                        @endforeach
                    </svg>
                    <div class="absolute flex flex-col items-center text-center">
                        <span class="text-xl font-black text-slate-800 tracking-tighter">{{ number_format($kpis['postulantes']) }}</span>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Total</span>
                    </div>
                </div>

                <div class="w-full space-y-2 mt-6 text-xs font-semibold">
                    @foreach($postulantes_carrera as $idx => $pc)
                        @php
                            $pct = $kpis['postulantes'] > 0 ? round($pc->total / $kpis['postulantes'] * 100) : 0;
                            $bgClass = $bgColors[$idx % count($bgColors)];
                        @endphp
                        <div class="flex justify-between items-center py-1 border-b border-slate-50">
                            <span class="text-slate-500 flex items-center">
                                <span class="w-2 h-2 rounded {{ $bgClass }} mr-2"></span>
                                {{ Str::limit($pc->nombre_carrera, 25) }}
                            </span>
                            <span class="text-slate-800 font-extrabold">{{ number_format($pc->total) }} ({{ $pct }}%)</span>
                        </div>
                    @endforeach
                </div>
                @else
                    <p class="text-xs text-slate-400 font-semibold text-center py-6">Sin datos de inscripciones por carrera.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Active meritocratic process Form -->
    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <div class="flex items-center space-x-3 mb-6">
            <div class="w-10 h-10 rounded-2xl bg-rose-50 text-[#c1121f] flex items-center justify-center text-lg flex-shrink-0 shadow-inner">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-850">Asignación Meritocrática de Cupos</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Ejecuta el proceso meritocrático en PostgreSQL para asignar cupos vacíos basados en calificaciones finales.</p>
            </div>
        </div>

        <form action="{{ route('admin.cupos.assign') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end bg-slate-50 p-6 rounded-2xl border border-slate-150/60">
            @csrf

            <!-- Carrera -->
            <div class="space-y-2">
                <label for="id_carrera" class="text-xs font-bold text-slate-700">Seleccionar Carrera</label>
                <select name="id_carrera" id="id_carrera" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-xs font-semibold text-slate-800 shadow-sm">
                    <option value="">Seleccione una carrera</option>
                    @foreach($carreras as $car)
                        <option value="{{ $car->id_carrera }}">{{ $car->nombre_carrera }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Gestion -->
            <div class="space-y-2">
                <label for="id_gestion" class="text-xs font-bold text-slate-700">Seleccionar Gestión</label>
                <select name="id_gestion" id="id_gestion" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 text-xs font-semibold text-slate-800 shadow-sm">
                    <option value="">Seleccione una gestión</option>
                    @foreach($gestiones as $ges)
                        <option value="{{ $ges->id_gestion }}">{{ $ges->anio }} - Período {{ $ges->periodo }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Button -->
            <button type="submit" class="w-full bg-[#c1121f] hover:bg-[#a80f1a] text-white py-3 px-4 rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all flex items-center justify-center space-x-2">
                <i class="fa-solid fa-bolt text-sm"></i>
                <span>Ejecutar Algoritmo de Adjudicación</span>
            </button>
        </form>
    </div>

    <!-- Recent Postulantes Table from real DB -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-850">Últimos Postulantes Registrados</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Monitoreo de expedientes recién ingresados al sistema.</p>
            </div>
            <span class="text-[10px] text-slate-400 font-bold border border-slate-100 bg-slate-50/50 px-3 py-1 rounded-full">Últimos 5</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                        <th class="py-4 px-6">Postulante</th>
                        <th class="py-4 px-6">Cédula Identidad (CI)</th>
                        <th class="py-4 px-6">Correo</th>
                        <th class="py-4 px-6">Fecha Registro</th>
                        <th class="py-4 px-6 text-center">Estado Inscripción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                    @forelse($ultimos_postulantes as $post)
                        @php
                            $words = explode(' ', $post->persona->nombre_completo);
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                            } else if (count($words) == 1) {
                                $initials = strtoupper(substr($words[0], 0, 2));
                            } else {
                                $initials = 'PE';
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-[11px] mr-3 flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                    <span class="font-extrabold text-slate-800">{{ $post->persona->nombre_completo }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-650">{{ $post->persona->ci }}</td>
                            <td class="py-4 px-6 text-slate-450 font-medium">{{ $post->persona->correo }}</td>
                            <td class="py-4 px-6 font-medium text-slate-500">{{ \Carbon\Carbon::parse($post->fecha_registro)->format('d/m/Y') }}</td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-3 py-1 rounded-full text-[9px] font-bold inline-block
                                    @if($post->estado_inscripcion === 'Admitido') bg-emerald-50 text-emerald-600
                                    @elseif($post->estado_inscripcion === 'Asignado') bg-blue-50 text-blue-600
                                    @elseif($post->estado_inscripcion === 'Pendiente') bg-amber-50 text-amber-600
                                    @else bg-rose-50 text-rose-600 @endif">
                                    {{ $post->estado_inscripcion }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">No hay postulantes registrados actualmente.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
