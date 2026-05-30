<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CUP Examen de Admisión - UAGRM</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for beautiful icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800">
    
    <!-- Toast Notifications -->
    @if(session('success'))
        <div class="fixed top-4 right-4 z-50 flex items-center p-4 mb-4 text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-100 shadow-xl max-w-md transition-all animate-bounce" id="toast-success">
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="ml-3 text-sm font-medium pr-8">{{ session('success') }}</div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg focus:ring-2 focus:ring-emerald-400 p-1.5 hover:bg-emerald-100 inline-flex h-8 w-8" onclick="document.getElementById('toast-success').style.display='none'">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="fixed top-4 right-4 z-50 flex flex-col p-4 mb-4 text-rose-800 rounded-xl bg-rose-50 border border-rose-100 shadow-xl max-w-md" id="toast-danger">
            <div class="flex items-center">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-rose-500 bg-rose-100 rounded-lg">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="ml-3 text-sm font-semibold">Ha ocurrido un error</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-rose-50 text-rose-500 rounded-lg focus:ring-2 focus:ring-rose-400 p-1.5 hover:bg-rose-100 inline-flex h-8 w-8" onclick="document.getElementById('toast-danger').style.display='none'">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="mt-2 pl-11 text-xs list-disc">
                @foreach ($errors->all() as $error)
                    <div>• {{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    @php
        $user = Auth::user();
        $isDocente = $user && $user->isDocente();
        $isSuperAdmin = $user && $user->isSuperAdmin();
        $isAdmin = $user && !$user->isSuperAdmin() && $user->isAdmin();
        $isPostulante = $user && $user->isPostulante();

        // Determine styles based on role
        if ($isDocente) {
            $sidebarBg = 'from-[#0f5132] to-[#082a1a]';
            $sidebarBorder = 'border-emerald-900/40';
            $activeClass = 'bg-[#10b981] text-white shadow-lg';
            $inactiveClass = 'text-emerald-100 hover:bg-emerald-900/40 hover:text-white';
            $logoBorder = 'border-emerald-500';
            $logoCapText = 'text-emerald-700';
            $greekPillarBg = 'bg-emerald-950/40 border-emerald-900/20';
            $pillarIcon = 'text-emerald-900/10';
            $sloganTextColor = 'text-emerald-400';
            $sloganSubColor = 'text-emerald-200';
            $profileBadgeBg = 'bg-emerald-600';
            $profileTextClass = 'text-emerald-400';
            $logoutBtnClass = 'text-emerald-300 hover:text-red-400';
            $bannerRoleColor = 'text-emerald-600';
            $badgeAbbr = 'DO';
        } elseif ($isSuperAdmin) {
            $sidebarBg = 'from-[#002855] to-[#001f3f]';
            $sidebarBorder = 'border-blue-900/40';
            $activeClass = 'bg-[#0284c7] text-white shadow-lg';
            $inactiveClass = 'text-blue-100 hover:bg-blue-900/40 hover:text-white';
            $logoBorder = 'border-red-500';
            $logoCapText = 'text-[#002855]';
            $greekPillarBg = 'bg-blue-950/40 border-blue-900/20';
            $pillarIcon = 'text-blue-900/10';
            $sloganTextColor = 'text-red-400';
            $sloganSubColor = 'text-blue-200';
            $profileBadgeBg = 'bg-blue-600';
            $profileTextClass = 'text-blue-400';
            $logoutBtnClass = 'text-blue-300 hover:text-red-400';
            $bannerRoleColor = 'text-blue-600';
            $badgeAbbr = 'SA';
        } elseif ($isPostulante) {
            $sidebarBg = 'from-[#0b1d33] to-[#071424]'; // Solid deep dark navy blue
            $sidebarBorder = 'border-slate-800/50';
            $activeClass = 'bg-[#0066ff] text-white shadow-md font-bold'; // Vibrant blue button
            $inactiveClass = 'text-slate-300 hover:bg-slate-800/40 hover:text-white';
            $logoBorder = 'border-transparent';
            $logoCapText = 'text-blue-500';
            $greekPillarBg = 'bg-slate-900/40 border-slate-800/20';
            $pillarIcon = 'text-slate-900/10';
            $sloganTextColor = 'text-slate-400';
            $sloganSubColor = 'text-slate-200';
            $profileBadgeBg = 'bg-[#0066ff]';
            $profileTextClass = 'text-blue-400';
            $logoutBtnClass = 'text-slate-400 hover:text-red-400';
            $bannerRoleColor = 'text-[#0066ff]';
            $badgeAbbr = 'PE'; // Postulante / Estudiante
        } else {
            // Standard Admin
            $sidebarBg = 'from-[#0d3b66] to-[#051c33]';
            $sidebarBorder = 'border-blue-900/40';
            $activeClass = 'bg-blue-600 text-white shadow-lg';
            $inactiveClass = 'text-blue-100 hover:bg-blue-900/40 hover:text-white';
            $logoBorder = 'border-red-500';
            $logoCapText = 'text-[#0d3b66]';
            $greekPillarBg = 'bg-blue-950/40 border-blue-900/20';
            $pillarIcon = 'text-blue-900/10';
            $sloganTextColor = 'text-red-400';
            $sloganSubColor = 'text-blue-200';
            $profileBadgeBg = 'bg-blue-600';
            $profileTextClass = 'text-blue-400';
            $logoutBtnClass = 'text-blue-300 hover:text-red-400';
            $bannerRoleColor = 'text-blue-600';
            $badgeAbbr = 'AD';
        }
    @endphp

    <div class="flex h-full overflow-hidden">
        
        <!-- SIDEBAR -->
        @auth
        @if(request()->routeIs('dashboard'))
        <aside class="w-72 bg-gradient-to-b {{ $sidebarBg }} text-white flex flex-col flex-shrink-0 shadow-2xl transition-all">
            <!-- Header Brand -->
            <div class="p-6 flex items-center space-x-3 border-b {{ $sidebarBorder }}">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg border-2 {{ $logoBorder }}">
                    <i class="fa-solid fa-graduation-cap {{ $logoCapText }} text-xl"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-wider leading-tight text-white uppercase">U.A.G.R.M.</h1>
                    <span class="text-xs text-blue-300 font-medium">FACULTAD DE INGENIERÍA</span>
                </div>
            </div>

            @if($isPostulante)
            <div class="pt-6 px-6 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Estudiante</div>
            @endif

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                
                @if($isSuperAdmin)
                    <!-- SUPERADMINISTRADOR LINKS -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-chart-line text-lg w-6"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- ADMISIÓN COLLAPSIBLE MENU -->
                    <div x-data="{ open: {{ request()->routeIs('admin.inscripciones*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.inscripciones*') ? $activeClass : $inactiveClass }}">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-id-card-clip text-lg w-6"></i>
                                <span>Admisión</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1" style="display: none;">
                            <a href="{{ route('admin.inscripciones') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.inscripciones') && !request()->route('id') ? 'bg-white/10 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-900/20 hover:text-white' }}">
                                <i class="fa-solid fa-table-list text-xs w-5"></i>
                                <span>Lista de Postulantes</span>
                            </a>
                            <a href="{{ route('admin.estado_postulante') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.estado_postulante') ? 'bg-white/10 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-900/20 hover:text-white' }}">
                                <i class="fa-solid fa-user-check text-xs w-5"></i>
                                <span>Estado del Postulante</span>
                            </a>

                            
                            @if(request()->routeIs('admin.inscripciones.detail') || request()->routeIs('admin.inscripciones.update') || request()->routeIs('admin.inscripciones.validate'))
                                <a href="{{ route('admin.inscripciones.detail', request()->route('id')) }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 bg-white/10 text-white shadow-sm">
                                    <i class="fa-solid fa-file-signature text-xs w-5"></i>
                                    <span>Inscripción</span>
                                </a>
                                <a href="{{ route('admin.inscripciones.detail', request()->route('id')) }}#section-documentos" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 text-blue-100 hover:bg-blue-900/20 hover:text-white">
                                    <i class="fa-solid fa-file-invoice text-xs w-5"></i>
                                    <span>Documentos</span>
                                </a>
                                <a href="{{ route('admin.inscripciones.detail', request()->route('id')) }}#section-pagos" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 text-blue-100 hover:bg-blue-900/20 hover:text-white">
                                    <i class="fa-solid fa-credit-card text-xs w-5"></i>
                                    <span>Pago</span>
                                </a>
                            @else
                                <span class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-300/40 cursor-not-allowed" title="Seleccione un postulante para habilitar esta pestaña">
                                    <i class="fa-solid fa-file-signature text-xs w-5"></i>
                                    <span>Inscripción</span>
                                </span>
                                <span class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-300/40 cursor-not-allowed" title="Seleccione un postulante para habilitar esta pestaña">
                                    <i class="fa-solid fa-file-invoice text-xs w-5"></i>
                                    <span>Documentos</span>
                                </span>
                                <span class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-300/40 cursor-not-allowed" title="Seleccione un postulante para habilitar esta pestaña">
                                    <i class="fa-solid fa-credit-card text-xs w-5"></i>
                                    <span>Pago</span>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase tracking-widest px-4">Administración</div>
                    
                    <a href="{{ route('admin.usuarios') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.usuarios') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-users text-lg w-6"></i>
                        <span>Usuarios y Personas</span>
                    </a>
                    <a href="{{ route('admin.gestionar.documentos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.gestionar.documentos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-folder-tree text-lg w-6"></i>
                        <span>Gestionar Documentos</span>
                    </a>
                    <a href="{{ route('admin.carreras') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.carreras') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-hotel text-lg w-6"></i>
                        <span>Carreras</span>
                    </a>
                    <a href="{{ route('admin.aulas') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.aulas') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-school text-lg w-6"></i>
                        <span>Aulas</span>
                    </a>
                    <a href="{{ route('admin.materias') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.materias') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-book-open text-lg w-6"></i>
                        <span>Materias</span>
                    </a>
                    <a href="{{ route('admin.grupos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.grupos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-people-group text-lg w-6"></i>
                        <span>Grupos</span>
                    </a>
                    <a href="{{ route('admin.cupos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.cupos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-circle-check text-lg w-6"></i>
                        <span>Asignación de Cupos</span>
                    </a>

                    <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase tracking-widest px-4">Procesamiento</div>
                    <a href="{{ route('admin.documentos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.documentos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-file-signature text-lg w-6"></i>
                        <span>Validar Documentos</span>
                    </a>
                    <a href="{{ route('admin.pagos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.pagos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-credit-card text-lg w-6"></i>
                        <span>Validar Pagos</span>
                    </a>

                    <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase tracking-widest px-4">Seguridad y Auditoría</div>
                    <a href="{{ route('admin.bitacora') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.bitacora') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-shield-halved text-lg w-6"></i>
                        <span>Bitácora de Auditoría</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-gears text-lg w-6"></i>
                        <span>Configuración del Sistema</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-database text-lg w-6"></i>
                        <span>Respaldo y Seguridad</span>
                    </a>

                @elseif($isDocente)
                    <!-- DOCENTE LINKS -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-chart-line text-lg w-6"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <div class="pt-4 pb-2 text-[10px] font-bold text-emerald-400 uppercase tracking-widest px-4">Docencia</div>
                    
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-chalkboard-user text-lg w-6"></i>
                        <span>Mis Asignaturas</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-file-signature text-lg w-6"></i>
                        <span>Evaluaciones y Notas</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-users text-lg w-6"></i>
                        <span>Postulantes</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-book text-lg w-6"></i>
                        <span>Materiales</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ $inactiveClass }}">
                        <i class="fa-solid fa-file-lines text-lg w-6"></i>
                        <span>Reportes</span>
                    </a>

                @elseif($isPostulante)
                    <!-- POSTULANTE LINKS -->
                    <a href="{{ route('postulante.dashboard') }}?tab=panel" id="sidebar-tab-panel" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 sidebar-postulante-btn {{ request()->query('tab') == 'panel' || !request()->has('tab') ? $activeClass : $inactiveClass }}" onclick="switchSidebarTab(event, 'tab-panel')">
                        <i class="fa-solid fa-house text-lg w-6"></i>
                        <span>Mi Panel</span>
                    </a>
                    <a href="{{ route('postulante.progreso') }}" id="sidebar-tab-inscripcion" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('postulante.progreso') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-user-pen text-lg w-6"></i>
                        <span>Mi Inscripción</span>
                    </a>
                    <a href="{{ route('postulante.dashboard') }}?tab=pagos" id="sidebar-tab-pagos" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 sidebar-postulante-btn {{ request()->query('tab') == 'pagos' ? $activeClass : $inactiveClass }}" onclick="switchSidebarTab(event, 'tab-pagos')">
                        <i class="fa-solid fa-credit-card text-lg w-6"></i>
                        <span>Estado de Pagos</span>
                    </a>
                    <a href="{{ route('postulante.dashboard') }}?tab=evaluaciones" id="sidebar-tab-evaluaciones" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 sidebar-postulante-btn {{ request()->query('tab') == 'evaluaciones' ? $activeClass : $inactiveClass }}" onclick="switchSidebarTab(event, 'tab-evaluaciones')">
                        <i class="fa-solid fa-pen-to-square text-lg w-6"></i>
                        <span>Mis Evaluaciones</span>
                    </a>
                    <a href="{{ route('postulante.dashboard') }}?tab=resultados" id="sidebar-tab-resultados" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 sidebar-postulante-btn {{ request()->query('tab') == 'resultados' ? $activeClass : $inactiveClass }}" onclick="switchSidebarTab(event, 'tab-resultados')">
                        <i class="fa-solid fa-chart-simple text-lg w-6"></i>
                        <span>Resultados</span>
                    </a>
                    <a href="{{ route('postulante.dashboard') }}?tab=notificaciones" id="sidebar-tab-notificaciones" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 sidebar-postulante-btn {{ request()->query('tab') == 'notificaciones' ? $activeClass : $inactiveClass }}" onclick="switchSidebarTab(event, 'tab-notificaciones')">
                        <i class="fa-solid fa-bell text-lg w-6"></i>
                        <span>Notificaciones</span>
                    </a>
                    <a href="{{ route('postulante.dashboard') }}?tab=documentos" id="sidebar-tab-documentos" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 sidebar-postulante-btn {{ request()->query('tab') == 'documentos' ? $activeClass : $inactiveClass }}" onclick="switchSidebarTab(event, 'tab-documentos')">
                        <i class="fa-solid fa-folder-open text-lg w-6"></i>
                        <span>Documentos</span>
                    </a>
                @else
                    <!-- ADMINISTRADOR LINKS -->
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('dashboard') || request()->routeIs('*.dashboard') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-chart-line text-lg w-6"></i>
                        <span>Dashboard</span>
                    </a>

                    <!-- ADMISIÓN COLLAPSIBLE MENU -->
                    <div x-data="{ open: {{ request()->routeIs('admin.inscripciones*') ? 'true' : 'false' }} }" class="space-y-1">
                        <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.inscripciones*') ? $activeClass : $inactiveClass }}">
                            <div class="flex items-center space-x-3">
                                <i class="fa-solid fa-id-card-clip text-lg w-6"></i>
                                <span>Admisión</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-collapse class="pl-4 space-y-1 mt-1" style="display: none;">
                            <a href="{{ route('admin.inscripciones') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.inscripciones') && !request()->route('id') ? 'bg-white/10 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-900/20 hover:text-white' }}">
                                <i class="fa-solid fa-table-list text-xs w-5"></i>
                                <span>Lista de Postulantes</span>
                            </a>
                            <a href="{{ route('admin.estado_postulante') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.estado_postulante') ? 'bg-white/10 text-white shadow-sm' : 'text-blue-100 hover:bg-blue-900/20 hover:text-white' }}">
                                <i class="fa-solid fa-user-check text-xs w-5"></i>
                                <span>Estado del Postulante</span>
                            </a>

                            
                            @if(request()->routeIs('admin.inscripciones.detail') || request()->routeIs('admin.inscripciones.update') || request()->routeIs('admin.inscripciones.validate'))
                                <a href="{{ route('admin.inscripciones.detail', request()->route('id')) }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 bg-white/10 text-white shadow-sm">
                                    <i class="fa-solid fa-file-signature text-xs w-5"></i>
                                    <span>Inscripción</span>
                                </a>
                                <a href="{{ route('admin.inscripciones.detail', request()->route('id')) }}#section-documentos" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 text-blue-100 hover:bg-blue-900/20 hover:text-white">
                                    <i class="fa-solid fa-file-invoice text-xs w-5"></i>
                                    <span>Documentos</span>
                                </a>
                                <a href="{{ route('admin.inscripciones.detail', request()->route('id')) }}#section-pagos" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold transition-all duration-200 text-blue-100 hover:bg-blue-900/20 hover:text-white">
                                    <i class="fa-solid fa-credit-card text-xs w-5"></i>
                                    <span>Pago</span>
                                </a>
                            @else
                                <span class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-300/40 cursor-not-allowed" title="Seleccione un postulante para habilitar esta pestaña">
                                    <i class="fa-solid fa-file-signature text-xs w-5"></i>
                                    <span>Inscripción</span>
                                </span>
                                <span class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-300/40 cursor-not-allowed" title="Seleccione un postulante para habilitar esta pestaña">
                                    <i class="fa-solid fa-file-invoice text-xs w-5"></i>
                                    <span>Documentos</span>
                                </span>
                                <span class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-xs font-bold text-blue-300/40 cursor-not-allowed" title="Seleccione un postulante para habilitar esta pestaña">
                                    <i class="fa-solid fa-credit-card text-xs w-5"></i>
                                    <span>Pago</span>
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase tracking-widest px-4">Administración</div>
                    
                    <a href="{{ route('admin.usuarios') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.usuarios') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-users text-lg w-6"></i>
                        <span>Usuarios y Personas</span>
                    </a>
                    <a href="{{ route('admin.gestionar.documentos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.gestionar.documentos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-folder-tree text-lg w-6"></i>
                        <span>Gestionar Documentos</span>
                    </a>
                    <a href="{{ route('admin.carreras') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.carreras') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-hotel text-lg w-6"></i>
                        <span>Carreras</span>
                    </a>
                    <a href="{{ route('admin.aulas') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.aulas') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-school text-lg w-6"></i>
                        <span>Aulas</span>
                    </a>
                    <a href="{{ route('admin.materias') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.materias') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-book-open text-lg w-6"></i>
                        <span>Materias</span>
                    </a>
                    <a href="{{ route('admin.grupos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.grupos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-people-group text-lg w-6"></i>
                        <span>Grupos</span>
                    </a>
                    <a href="{{ route('admin.cupos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.cupos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-circle-check text-lg w-6"></i>
                        <span>Asignación de Cupos</span>
                    </a>

                    <div class="pt-4 pb-2 text-[10px] font-bold text-blue-400 uppercase tracking-widest px-4">Procesamiento</div>
                    <a href="{{ route('admin.documentos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.documentos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-file-signature text-lg w-6"></i>
                        <span>Validar Documentos</span>
                    </a>
                    <a href="{{ route('admin.pagos') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.pagos') ? $activeClass : $inactiveClass }}">
                        <i class="fa-solid fa-credit-card text-lg w-6"></i>
                        <span>Validar Pagos</span>
                    </a>
                @endif
            </nav>

            <!-- Translucent Greek Pillar Slogan (Mockup-identical detail!) -->
            @if(!$isPostulante)
            <div class="p-6 m-4 {{ $greekPillarBg }} rounded-2xl border text-center relative overflow-hidden shadow-inner transition-colors">
                <i class="fa-solid fa-landmark text-6xl {{ $pillarIcon }} absolute right-4 bottom-2 pointer-events-none transition-colors"></i>
                <h4 class="text-xs font-semibold {{ $sloganTextColor }} tracking-wider transition-colors">FORMAMOS PROFESIONALES</h4>
                <p class="text-[10px] {{ $sloganSubColor }} font-medium mt-1 transition-colors">Transformamos el futuro de la sociedad.</p>
            </div>
            @endif

            <!-- Footer user profile details -->
            <div class="p-4 border-t {{ $sidebarBorder }} flex items-center justify-between transition-colors">
                <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-10 h-10 rounded-full {{ $profileBadgeBg }} text-white flex items-center justify-center font-bold text-sm shadow-md flex-shrink-0 transition-colors">
                        {{ strtoupper(substr(Auth::user()->persona->nombre, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-white truncate">{{ Auth::user()->persona->nombre_completo }}</div>
                        <div class="text-[10px] {{ $profileTextClass }} truncate transition-colors">
                            @if($isPostulante) Estudiante
                            @else {{ Auth::user()->rol->nombre_rol }}
                            @endif
                        </div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0 ml-2">
                    @csrf
                    <button type="submit" class="p-2 {{ $logoutBtnClass }} transition-colors" title="Cerrar Sesión">
                        <i class="fa-solid fa-power-off text-lg"></i>
                    </button>
                </form>
            </div>
        </aside>
        @endif
        @endauth

        <!-- MAIN WINDOW -->
        <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
            <!-- Header bar for authenticated users -->
            @auth
            <header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-8 shadow-sm flex-shrink-0 z-10">
                <div class="flex items-center space-x-4">
                    @if(!request()->routeIs('dashboard'))
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200 transition-all duration-200 hover:-translate-x-0.5 mr-3" title="Volver al Dashboard">
                            <i class="fa-solid fa-arrow-left text-sm"></i>
                        </a>
                    @endif
                    <h2 class="text-xl font-bold text-slate-800">
                        @if($isSuperAdmin) Panel Superadministrador
                        @elseif($isDocente) Panel Docente
                        @elseif($isAdmin) Panel Administrativo
                        @elseif($isPostulante) Panel Estudiante
                        @else Registro de Examen de Admisión CUP
                        @endif
                    </h2>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="text-xs text-slate-400 font-semibold flex items-center">
                        <i class="fa-regular fa-calendar-days mr-2 text-slate-400"></i>
                        {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                    </span>
                    <div class="h-6 w-[1px] bg-slate-200"></div>
                    <div class="flex items-center space-x-3">
                        <!-- Role Badge in Header -->
                        <div class="w-10 h-10 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center font-bold text-xs shadow-inner {{ $bannerRoleColor }}">
                            {{ $badgeAbbr }}
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-slate-800">{{ Auth::user()->persona->nombre_completo }}</div>
                            <div class="text-[10px] font-semibold {{ $bannerRoleColor }} uppercase tracking-wider transition-colors">
                                @if($isPostulante) ESTUDIANTE
                                @else {{ Auth::user()->rol->nombre_rol }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            @endauth

            <!-- Route views insertion -->
            <div class="flex-1 p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function switchSidebarTab(event, tabId) {
            // Reactive tab switching if we are on the student dashboard!
            if (window.location.pathname.includes('/postulante/dashboard')) {
                event.preventDefault();
                if (typeof switchTab === 'function') {
                    switchTab(tabId);
                }
            }
        }
    </script>
</body>
</html>
