@extends('layouts.app')

@section('content')
<div class="space-y-8 pb-12">
    
    <!-- Welcome Header & Time -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Panel Superadministrador</h1>
            <p class="text-xs text-slate-400 font-semibold">Resumen general del sistema y auditoría de seguridad en tiempo real.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2.5 bg-white border border-slate-100 rounded-2xl text-xs font-bold text-slate-600 shadow-sm flex items-center">
                <i class="fa-regular fa-calendar-days mr-2 text-slate-400"></i>
                {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
            </span>
            <span class="px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-extrabold rounded-full border border-blue-100 flex items-center">
                <span class="w-2 h-2 rounded-full bg-blue-600 mr-1.5 animate-pulse"></span>
                Modo SuperAdmin
            </span>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <!-- Card 1: Usuarios -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="z-10">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ number_format($stats['usuarios']) }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Usuarios</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Totales registrados</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="{{ route('admin.usuarios') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Card 2: Roles -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div class="z-10">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ $stats['roles'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Roles</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Configurados</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="#" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Card 3: Postulantes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div class="z-10">
                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-855 mt-4 tracking-tight">{{ number_format($stats['postulantes']) }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Postulantes</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Totales registrados</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="{{ route('admin.documentos') }}" class="text-[10px] font-bold text-purple-600 hover:text-purple-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Card 4: Reportes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div class="z-10">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-file-invoice"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ $stats['reportes'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Reportes</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Generados</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="#" class="text-[10px] font-bold text-amber-600 hover:text-amber-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>

        <!-- Card 5: Estado del Sistema -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-all relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-slate-50 text-7xl pointer-events-none group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-square-poll-vertical"></i>
            </div>
            <div class="z-10">
                <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl shadow-sm flex-shrink-0">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-850 mt-4 tracking-tight">{{ $stats['estado_sistema'] }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Estado del Sistema</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Operativo</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4 z-10">
                <a href="#" class="text-[10px] font-bold text-cyan-600 hover:text-cyan-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Section: Graphs and logs -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Estadísticas Generales Sparklines (2/3 width) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 lg:col-span-2 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-6">
                <div>
                    <h3 class="text-sm font-black text-slate-850">Estadísticas Generales</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Comparativa del proceso actual vs periodo anterior.</p>
                </div>
                <select class="px-3 py-1.5 bg-slate-50 border border-slate-150 rounded-xl text-[10px] font-bold text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Este año</option>
                    <option>Año pasado</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 flex-1">
                <!-- Sparkline 1: Inscritos (Pendiente) -->
                <div class="flex flex-col justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:shadow-inner transition-all">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Inscritos</span>
                        <h4 class="text-base font-black text-slate-850 mt-1">{{ number_format($distribucion['inscritos']) }}</h4>
                        <span class="text-[9px] font-bold text-blue-500 flex items-center mt-1">
                            <i class="fa-solid fa-users mr-0.5"></i>
                            {{ $distribucion['inscritos_pct'] }}% del total
                        </span>
                    </div>
                    <div class="h-10 mt-4">
                        <svg class="w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
                            <path d="M0,25 Q15,10 30,20 T60,5 T90,15 T100,10" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round"></path>
                            <path d="M0,25 Q15,10 30,20 T60,5 T90,15 T100,10 L100,30 L0,30 Z" fill="url(#blue-gradient)" opacity="0.1"></path>
                            <defs><linearGradient id="blue-gradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#3b82f6"/><stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/></linearGradient></defs>
                        </svg>
                    </div>
                </div>

                <!-- Sparkline 2: Admitidos -->
                <div class="flex flex-col justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:shadow-inner transition-all">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Admitidos</span>
                        <h4 class="text-base font-black text-slate-855 mt-1">{{ number_format($distribucion['admitidos']) }}</h4>
                        <span class="text-[9px] font-bold text-emerald-500 flex items-center mt-1">
                            <i class="fa-solid fa-award mr-0.5"></i>
                            {{ $distribucion['admitidos_pct'] }}% del total
                        </span>
                    </div>
                    <div class="h-10 mt-4">
                        <svg class="w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
                            <path d="M0,28 Q20,15 40,25 T80,10 T100,8" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round"></path>
                            <path d="M0,28 Q20,15 40,25 T80,10 T100,8 L100,30 L0,30 Z" fill="url(#green-gradient)" opacity="0.1"></path>
                            <defs><linearGradient id="green-gradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#10b981"/><stop offset="100%" stop-color="#10b981" stop-opacity="0"/></linearGradient></defs>
                        </svg>
                    </div>
                </div>

                <!-- Sparkline 3: Asignados -->
                <div class="flex flex-col justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:shadow-inner transition-all">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Asignados</span>
                        <h4 class="text-base font-black text-slate-850 mt-1">{{ number_format($distribucion['asignados']) }}</h4>
                        <span class="text-[9px] font-bold text-amber-500 flex items-center mt-1">
                            <i class="fa-solid fa-user-check mr-0.5"></i>
                            {{ $distribucion['asignados_pct'] }}% del total
                        </span>
                    </div>
                    <div class="h-10 mt-4">
                        <svg class="w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
                            <path d="M0,20 Q25,25 50,15 T100,5" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"></path>
                            <path d="M0,20 Q25,25 50,15 T100,5 L100,30 L0,30 Z" fill="url(#yellow-gradient)" opacity="0.1"></path>
                            <defs><linearGradient id="yellow-gradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#f59e0b"/><stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/></linearGradient></defs>
                        </svg>
                    </div>
                </div>

                <!-- Sparkline 4: No Admitidos -->
                <div class="flex flex-col justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:shadow-inner transition-all">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">No Admitidos</span>
                        <h4 class="text-base font-black text-slate-850 mt-1">{{ number_format($distribucion['no_admitidos']) }}</h4>
                        <span class="text-[9px] font-bold text-rose-500 flex items-center mt-1">
                            <i class="fa-solid fa-user-xmark mr-0.5"></i>
                            {{ $distribucion['no_admitidos_pct'] }}% del total
                        </span>
                    </div>
                    <div class="h-10 mt-4">
                        <svg class="w-full h-full" viewBox="0 0 100 30" preserveAspectRatio="none">
                            <path d="M0,5 Q30,10 60,25 T100,28" fill="none" stroke="#ef4444" stroke-width="2" stroke-linecap="round"></path>
                            <path d="M0,5 Q30,10 60,25 T100,28 L100,30 L0,30 Z" fill="url(#red-gradient)" opacity="0.1"></path>
                            <defs><linearGradient id="red-gradient" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#ef4444"/><stop offset="100%" stop-color="#ef4444" stop-opacity="0"/></linearGradient></defs>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Actividad Reciente (1/3 width) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                <div>
                    <h3 class="text-sm font-black text-slate-850">Actividad Reciente</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Bitácora de seguridad del sistema</p>
                </div>
                <a href="{{ route('admin.bitacora') }}" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 transition-colors">Ver todo</a>
            </div>

            <!-- Activity List -->
            <div class="space-y-3.5 overflow-y-auto max-h-[220px] pr-1">
                @foreach($actividades as $act)
                    <div class="flex items-start space-x-3 p-2 hover:bg-slate-50 rounded-xl transition-colors">
                        <div class="w-8 h-8 rounded-lg {{ $act['bg'] }} flex items-center justify-center text-xs flex-shrink-0 shadow-sm">
                            <i class="fa-solid {{ $act['icono'] }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-[11px] font-extrabold text-slate-800 leading-normal">{{ $act['titulo'] }}</h4>
                            <span class="text-[9px] text-slate-400 font-semibold mt-0.5 block">{{ $act['tiempo'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Lower Section: System usage and health indicators -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Pie Chart - Uso del Sistema -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h3 class="text-sm font-black text-slate-850">Uso del Sistema</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Distribución de estados del servidor principal.</p>
            </div>

            <div class="flex flex-col items-center justify-center py-4 flex-1">
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <!-- Elegant Circular Pie Chart Progress -->
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-slate-100" stroke-width="4" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        
                        <!-- Operativo: 98% (Blue) -->
                        <path class="text-blue-500" stroke-dasharray="98, 100" stroke-width="4" stroke-linecap="round" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                    <!-- Core percentage -->
                    <div class="absolute flex flex-col items-center text-center">
                        <span class="text-2xl font-black text-slate-800 tracking-tighter">98%</span>
                        <span class="text-[8px] font-bold text-emerald-500 uppercase tracking-widest mt-0.5">Operativo</span>
                    </div>
                </div>

                <!-- Labels -->
                <div class="w-full grid grid-cols-3 gap-2 mt-6 text-center text-[10px] font-bold">
                    <div class="p-2 bg-blue-50/30 rounded-xl border border-blue-50">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block mr-1"></span>
                        <span class="text-slate-450 block sm:inline">Operativo</span>
                        <p class="font-black text-slate-800 mt-0.5">98%</p>
                    </div>
                    <div class="p-2 bg-amber-50/30 rounded-xl border border-amber-50">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block mr-1"></span>
                        <span class="text-slate-450 block sm:inline">Mantenim.</span>
                        <p class="font-black text-slate-800 mt-0.5">1%</p>
                    </div>
                    <div class="p-2 bg-rose-50/30 rounded-xl border border-rose-50">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 inline-block mr-1"></span>
                        <span class="text-slate-450 block sm:inline">Inactivo</span>
                        <p class="font-black text-slate-800 mt-0.5">1%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Actions for Superadmin (2/3 width) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 lg:col-span-2 flex flex-col justify-between">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h3 class="text-sm font-black text-slate-850">Panel de Control Global</h3>
                <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Acceso rápido a tareas y configuraciones generales del sistema.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-1">
                <!-- Backup & Recovery Card -->
                <div class="p-5 bg-gradient-to-br from-blue-50/40 to-indigo-50/40 border border-blue-100/50 rounded-2xl flex flex-col justify-between hover:shadow-sm transition-shadow">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-lg shadow-md flex-shrink-0">
                            <i class="fa-solid fa-database"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-blue-900 leading-tight">Respaldos y Seguridad</h4>
                            <p class="text-[9px] text-blue-500 font-bold mt-0.5">Último respaldo hace 5 horas</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-normal mb-4 font-semibold">Descarga una copia completa de la base de datos PostgreSQL, esquemas de tablas, triggers y datos de auditoría con un solo clic.</p>
                    <button class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[10px] font-bold shadow-md transition-colors flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                        <span>Generar Respaldo Completo</span>
                    </button>
                </div>

                <!-- System Config Card -->
                <div class="p-5 bg-gradient-to-br from-rose-50/40 to-pink-50/40 border border-rose-100/50 rounded-2xl flex flex-col justify-between hover:shadow-sm transition-shadow">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-[#c1121f] text-white flex items-center justify-center text-lg shadow-md flex-shrink-0">
                            <i class="fa-solid fa-sliders"></i>
                        </div>
                        <div>
                            <h4 class="text-xs font-black text-rose-900 leading-tight">Variables del Sistema</h4>
                            <p class="text-[9px] text-rose-500 font-bold mt-0.5">Gestión Académica Activa: 2026</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-500 leading-normal mb-4 font-semibold">Modifica los parámetros globales del examen CUP, rangos de aprobación, capacidad máxima de aulas y límites operativos.</p>
                    <button class="w-full py-2.5 bg-[#c1121f] hover:bg-[#a80f1a] text-white rounded-xl text-[10px] font-bold shadow-md transition-colors flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-gears"></i>
                        <span>Ajustar Configuración</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
