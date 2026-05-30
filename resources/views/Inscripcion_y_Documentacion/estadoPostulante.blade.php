<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mi Estado - CUP Sistema de Admisión</title>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    /* Ocultar el menú lateral global y hacer que el contenido ocupe el 100% */
    .sidebar, .sidebar-overlay {
        display: none !important;
    }
    .main-content, .main {
        margin-left: 0 !important;
        width: 100% !important;
    }
    .menu-toggle, .menu-btn {
        display: none !important;
    }
    .back-dashboard-btn {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background-color: #f1f5f9;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        margin-right: 12px;
    }
    .back-dashboard-btn:hover {
        background-color: #e2e8f0;
        color: #0f172a;
        transform: translateX(-2px);
    }
    .back-dashboard-btn i {
        font-size: 16px;
    }
    :root {
        --bg-main: #f8fafc;
        --sidebar-bg: #07153a;
        --sidebar-active: #e31c3d;
        --sidebar-hover: rgba(227, 28, 61, 0.15);
        --text-primary: #111827;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --border: #e5e7eb;
        --card-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        --radius: 14px;
        --accent-blue: #0052cc;
        --accent-red: #e31c3d;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background: var(--bg-main);
        color: var(--text-primary);
        min-height: 100vh;
    }

    .dashboard-container {
        display: flex;
        width: 100%;
        min-height: 100vh;
    }

    .sidebar {
        width: 250px;
        height: 100vh;
        background: var(--sidebar-bg);
        position: fixed;
        top: 0;
        left: 0;
        color: #fff;
        display: flex;
        flex-direction: column;
        z-index: 100;
    }

    .sidebar-header {
        height: 78px;
        display: flex;
        align-items: center;
        padding: 0 22px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .brand-icon {
        width: 36px;
        height: 36px;
        border: 4px solid var(--accent-red);
        transform: rotate(45deg);
        border-radius: 4px;
        position: relative;
    }

    .brand-icon::after {
        content: "";
        width: 8px;
        height: 8px;
        background: var(--accent-red);
        position: absolute;
        top: 8px;
        left: 8px;
        border-radius: 50%;
    }

    .brand-title {
        font-size: 26px;
        font-weight: 800;
        line-height: 1;
    }

    .brand-subtitle {
        font-size: 8px;
        color: #cbd5e1;
        font-weight: 500;
    }

    .sidebar-menu {
        padding: 20px 14px;
        overflow-y: auto;
        flex: 1;
    }

    .sidebar-section-title {
        margin: 18px 10px 9px;
        font-size: 10px;
        color: #8ea3c7;
        letter-spacing: 1.4px;
        text-transform: uppercase;
        font-weight: 700;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255,255,255,0.78);
        text-decoration: none;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
        transition: all .25s ease;
    }

    .sidebar-menu a i {
        width: 18px;
        text-align: center;
        font-size: 15px;
    }

    .sidebar-menu a:hover {
        background: var(--sidebar-hover);
        color: #fff;
    }

    .sidebar-menu a.active {
        background: var(--sidebar-active);
        color: #fff;
        box-shadow: 0 8px 18px rgba(0, 82, 204, 0.25);
    }

    .logout-link {
        margin-top: 20px;
        color: #f87171 !important;
    }

    .logout-link i {
        color: #f87171 !important;
    }

    .main-content {
        margin-left: 250px;
        width: calc(100% - 250px);
        min-height: 100vh;
    }

    .topbar {
        height: 72px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 34px;
        border-bottom: 1px solid #eef2f7;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }

    .menu-toggle {
        border: none;
        background: transparent;
        font-size: 20px;
        cursor: pointer;
        color: #111827;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 22px;
    }

    .profile-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 14px;
        color: #111827;
    }

    .profile-avatar {
        width: 36px;
        height: 36px;
        background: var(--sidebar-active);
        color: #fff;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
    }

    .content-body {
        padding: 26px 32px 40px;
    }

    .page-header {
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-size: 28px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 8px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 700;
    }

    .breadcrumb a {
        text-decoration: none;
        color: var(--sidebar-active);
    }

    .breadcrumb span {
        color: #6b7280;
    }

    .page-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .card {
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
        padding: 24px;
        margin-bottom: 24px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--accent-blue);
    }

    .status-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        font-weight: 600;
    }

    .status-item:last-child {
        border-bottom: none;
    }

    .status-label {
        color: var(--text-secondary);
    }

    .status-value {
        color: var(--text-primary);
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-green { background: #dcfce7; color: #15803d; }
    .badge-yellow { background: #fef3c7; color: #b45309; }
    .badge-blue { background: #eff6ff; color: #1d4ed8; }
    .badge-red { background: #fee2e2; color: #dc2626; }

    .warning-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        padding: 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .warning-box i {
        font-size: 18px;
        color: #64748b;
    }

    ul.item-list {
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    li.list-item {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .item-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .item-subtitle {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        margin-top: 2px;
    }

    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .page-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
</head>

<body>

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand">
                <div class="brand-icon"></div>
                <div class="brand-text">
                    <span class="brand-title">CUP</span>
                    <span class="brand-subtitle">PREUNIVERSITARIO</span>
                </div>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="sidebar-section-title">Inicio</div>
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Mi Admisión</div>
            <a href="{{ route('postulante.estado') }}" class="active">
                <i class="fa-solid fa-user-check"></i>
                <span>Mi Estado</span>
            </a>

            <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar Sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </aside>

    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <a href="{{ route('dashboard') }}" class="back-dashboard-btn" title="Volver al Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h2>Panel del Postulante</h2>
            </div>

            <div class="topbar-right">
                <div class="profile-pill">
                    <div class="profile-avatar">
                        {{ Auth::user()->persona ? strtoupper(substr(Auth::user()->persona->nombre, 0, 1)) : 'U' }}
                    </div>
                    <span>{{ Auth::user()->persona ? Auth::user()->persona->nombre_completo : Auth::user()->nombre_usuario }}</span>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <section class="content-body">

            <div class="page-header">
                <h1>Estado de mi Postulación</h1>
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <span>Mi Estado</span>
                </div>
            </div>

            <div class="page-grid">
                
                <!-- LEFT COLUMN -->
                <div>
                    <!-- INSCRIPCION CARD -->
                    <div class="card">
                        <div class="card-title">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <span>Detalle de Inscripción</span>
                        </div>
                        @if($inscripcion)
                            <div class="status-item">
                                <span class="status-label">Código de Inscripción</span>
                                <span class="status-value font-bold" style="color: var(--accent-blue);">{{ $inscripcion->codigo_inscripcion }}</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Fecha de Registro</span>
                                <span class="status-value">{{ $inscripcion->fecha_inscripcion }}</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Estado de la Inscripción</span>
                                <span>
                                    <span class="badge {{ $inscripcion->estado == 'Validado' ? 'badge-green' : ($inscripcion->estado == 'Pendiente' ? 'badge-yellow' : 'badge-red') }}">
                                        {{ $inscripcion->estado }}
                                    </span>
                                </span>
                            </div>
                        @else
                            <div class="warning-box">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Aún no tienes un registro de inscripción completo. Completa tu proceso en Admisiones.</span>
                            </div>
                        @endif
                    </div>

                    <!-- GRUPO CARD -->
                    <div class="card">
                        <div class="card-title">
                            <i class="fa-solid fa-school"></i>
                            <span>Grupo Asignado</span>
                        </div>
                        @if($grupo)
                            <div class="status-item">
                                <span class="status-label">Código de Grupo</span>
                                <span class="status-value font-bold" style="color: var(--accent-blue);">{{ $grupo->sigla_grupo }}</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Capacidad de Estudiantes</span>
                                <span class="status-value">{{ $grupo->cant_estudiantes }} / {{ $grupo->capacidad_max }}</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Aula Asignada</span>
                                <span class="status-value">
                                    @if($grupo->aula)
                                        {{ $grupo->aula->codigo_aula }} 
                                        @if($grupo->aula->edificio || $grupo->aula->piso)
                                            ({{ $grupo->aula->edificio }}{{ $grupo->aula->piso ? ', Piso ' . $grupo->aula->piso : '' }})
                                        @elseif($grupo->aula->ubicacion)
                                            ({{ $grupo->aula->ubicacion }})
                                        @endif
                                    @else
                                        Sin Aula Asignada
                                    @endif
                                </span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Modalidad</span>
                                <span class="status-value">{{ $grupo->modalidad->nombre_modalidad ?? 'Presencial' }}</span>
                            </div>
                            <div class="status-item">
                                <span class="status-label">Turno</span>
                                <span class="status-value">{{ $grupo->turno->nombre_turno ?? 'Mañana' }}</span>
                            </div>
                        @else
                            <div class="warning-box">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>No tienes un grupo asignado. La asignación se realiza de forma automática una vez tu pago haya sido aprobado e ingreses al sistema.</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <!-- PAGOS CARD -->
                    <div class="card">
                        <div class="card-title">
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Pagos Realizados</span>
                        </div>
                        @forelse($pagos as $pago)
                            <ul class="item-list">
                                <li class="list-item">
                                    <div>
                                        <div class="item-title">{{ $pago->metodo_pago }}</div>
                                        <div class="item-subtitle">Monto: Bs. {{ $pago->monto }} | Fecha: {{ $pago->fecha_pago }}</div>
                                    </div>
                                    <span class="badge {{ $pago->estado_pago == 'Pagado' ? 'badge-green' : ($pago->estado_pago == 'Pendiente' ? 'badge-yellow' : 'badge-red') }}">
                                        {{ $pago->estado_pago }}
                                    </span>
                                </li>
                            </ul>
                        @empty
                            <div class="warning-box">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>No has registrado ningún pago para tu inscripción.</span>
                            </div>
                        @endforelse
                    </div>

                    <!-- DOCUMENTOS CARD -->
                    <div class="card">
                        <div class="card-title">
                            <i class="fa-solid fa-folder-open"></i>
                            <span>Documentos Entregados</span>
                        </div>
                        <ul class="item-list">
                            @forelse($documentos as $doc)
                                <li class="list-item">
                                    <div>
                                        <div class="item-title">{{ $doc->tipo_documento }}</div>
                                        <div class="item-subtitle">Archivo: {{ $doc->nombre }} | Registro: {{ $doc->fecha_registro }}</div>
                                        @if($doc->observacion)
                                            <div style="font-size: 11px; color: var(--accent-red); margin-top: 4px; font-weight: 600;">Obs: {{ $doc->observacion }}</div>
                                        @endif
                                    </div>
                                    <span class="badge {{ $doc->estado == 'Validado' ? 'badge-green' : ($doc->estado == 'Pendiente' ? 'badge-yellow' : 'badge-red') }}">
                                        {{ $doc->estado }}
                                    </span>
                                </li>
                            @empty
                                <div class="warning-box">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                    <span>No has presentado ningún documento para validación.</span>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>

        </section>
    </main>

</div>

</body>
</html>
