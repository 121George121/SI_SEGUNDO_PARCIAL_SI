<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - CUP UAGRM</title>
<!-- Google Fonts: Outfit -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<!-- FontAwesome 6 for Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Chart.js for premium dynamic charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --bg-main: #f8fafc;
        --sidebar-bg: #07153a;
        --sidebar-active: #e31c3d;
        --sidebar-hover: rgba(227, 28, 61, 0.15);
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --accent-blue: #1e40af;
        --accent-red: #e31c3d;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        --card-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.08);
        --card-border: 1px solid #f1f5f9;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background-color: var(--bg-main);
        color: var(--text-primary);
        min-height: 100vh;
        display: flex;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Dashboard Layout */
    .dashboard-container {
        display: flex;
        width: 100%;
        min-height: 100vh;
    }

    /* Sidebar — FIXED so the page content scrolls behind it */
    .sidebar {
        width: 280px;
        background-color: var(--sidebar-bg);
        color: #ffffff;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        border-right: 1px solid rgba(255, 255, 255, 0.05);
        /* FIXED sidebar */
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 100;
        transition: transform 0.3s ease;
    }

    /* Mobile: sidebar hidden off-screen by default */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }
        .sidebar.open {
            transform: translateX(0);
        }
    }

    /* Overlay for mobile when sidebar is open */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.4);
        z-index: 99;
        backdrop-filter: blur(2px);
    }
    .sidebar-overlay.active {
        display: block;
    }

    .sidebar-header {
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: var(--sidebar-active);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 900;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(227, 28, 61, 0.3);
    }

    .logo-text {
        display: flex;
        flex-direction: column;
    }

    .logo-title {
        font-size: 24px;
        font-weight: 900;
        color: #ffffff;
        line-height: 1;
        letter-spacing: 0.5px;
    }

    .logo-title span {
        color: var(--sidebar-active);
    }

    .logo-subtitle {
        font-size: 9px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-top: 2px;
    }

    /* Sidebar menu with its OWN scroll */
    .sidebar-menu {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 20px 14px;
        /* enable smooth scrolling */
        scroll-behavior: smooth;
    }

    .sidebar-section-title {
        font-size: 10px;
        font-weight: 800;
        color: #4b6b94;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 20px 10px 10px 10px;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, 0.7);
        text-decoration: none;
        padding: 12px 16px;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 12px;
        transition: all 0.25s ease;
        margin-bottom: 4px;
    }

    .sidebar-menu a i {
        font-size: 16px;
        width: 20px;
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        transition: color 0.25s ease;
    }

    .sidebar-menu a:hover {
        background-color: var(--sidebar-hover);
        color: #ffffff;
    }

    .sidebar-menu a:hover i {
        color: #ffffff;
    }

    .sidebar-menu a.active {
        background-color: var(--sidebar-active);
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(227, 28, 61, 0.2);
    }

    .sidebar-menu a.active i {
        color: #ffffff;
    }

    /* Main content — offset by the fixed sidebar width, scrolls freely */
    .main-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        /* push right of fixed sidebar */
        margin-left: 280px;
        min-height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
    }

    @media (max-width: 1200px) {
        .sidebar {
            width: 240px;
        }
        .main-content {
            margin-left: 240px;
        }
    }

    /* Tablet / Mobile: full width, no sidebar offset */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
    }

    /* Top Bar Styling */
    .topbar {
        background-color: #ffffff;
        height: 70px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        flex-shrink: 0;
        position: sticky;
        top: 0;
        z-index: 50;
    }

    @media (max-width: 768px) {
        .topbar {
            padding: 0 16px;
        }
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .menu-toggle {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
        transition: color 0.2s;
    }

    .menu-toggle:hover {
        color: var(--text-primary);
    }

    .page-title {
        font-size: 18px;
        font-weight: 700;
        color: #0b1d4a;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .notification-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--text-secondary);
        cursor: pointer;
        position: relative;
        transition: color 0.2s;
    }

    .notification-btn:hover {
        color: var(--text-primary);
    }

    .notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background-color: var(--sidebar-active);
        color: #ffffff;
        font-size: 9px;
        font-weight: 700;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ffffff;
    }

    .profile-pill {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 12px;
        transition: background-color 0.2s;
    }

    .profile-pill:hover {
        background-color: #f1f5f9;
    }

    .profile-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background-color: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-blue);
        font-size: 16px;
        font-weight: 700;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px var(--accent-blue);
        overflow: hidden;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .profile-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
    }

    /*.profile-role {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-secondary);
    }*/

    .profile-chevron {
        font-size: 12px;
        color: var(--text-secondary);
    }

    /* Content Styling */
    .content-body {
        padding: 32px;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    @media (max-width: 768px) {
        .content-body {
            padding: 20px 16px;
            gap: 20px;
        }
    }

    /* Welcome Section */
    .welcome-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
    }

    @media (max-width: 600px) {
        .welcome-left h2 {
            font-size: 20px;
        }
        .date-badge {
            width: 100%;
        }
    }

    .welcome-left h2 {
        font-size: 26px;
        font-weight: 800;
        color: #0b1d4a;
        letter-spacing: -0.5px;
    }

    .welcome-left p {
        font-size: 14px;
        color: var(--text-secondary);
        margin-top: 4px;
    }

    .date-badge {
        background-color: #ffffff;
        border: var(--card-border);
        border-radius: 16px;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--card-shadow);
    }

    .date-badge i {
        font-size: 18px;
        color: var(--text-secondary);
    }

    .date-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .date-primary {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .date-secondary {
        font-size: 10.5px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    /* KPI Grid */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    @media (max-width: 600px) {
        .kpi-grid {
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .kpi-card {
            padding: 16px;
            gap: 12px;
        }
        .kpi-value {
            font-size: 22px;
        }
        .kpi-icon-wrapper {
            width: 42px;
            height: 42px;
            font-size: 16px;
        }
    }

    .kpi-card {
        background-color: #ffffff;
        border: var(--card-border);
        border-radius: 20px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: var(--card-shadow);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--card-shadow-hover);
    }

    .kpi-icon-wrapper {
        width: 54px;
        height: 54px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .kpi-icon-wrapper.blue {
        background-color: rgba(30, 64, 175, 0.1);
        color: var(--accent-blue);
    }

    .kpi-icon-wrapper.red {
        background-color: rgba(227, 28, 61, 0.1);
        color: var(--accent-red);
    }

    .kpi-details {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .kpi-title {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        margin: 4px 0;
        letter-spacing: -0.5px;
    }

    .kpi-trend {
        font-size: 11px;
        font-weight: 600;
        color: #10b981;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Charts Row */
    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 32px;
    }

    @media (max-width: 1024px) {
        .charts-row {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background-color: #ffffff;
        border: var(--card-border);
        border-radius: 24px;
        padding: 28px;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-direction: column;
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .chart-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #0b1d4a;
    }

    .donut-container {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 32px;
        flex: 1;
    }

    @media (max-width: 600px) {
        .donut-container {
            flex-direction: column;
            gap: 20px;
        }
    }

    .donut-chart-wrapper {
        width: 180px;
        height: 180px;
        position: relative;
    }

    .donut-labels {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }

    .donut-label-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 13px;
        font-weight: 600;
        padding: 6px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .donut-label-left {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
    }

    .donut-color-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }

    .donut-color-dot.blue { background-color: #1e40af; }
    .donut-color-dot.red { background-color: #e31c3d; }
    .donut-color-dot.dark-blue { background-color: #0c2c5a; }
    .donut-color-dot.grey { background-color: #cbd5e1; }

    .donut-label-value {
        color: var(--text-primary);
        font-weight: 700;
    }

    .donut-total {
        margin-top: 16px;
        font-size: 13px;
        font-weight: 700;
        color: var(--text-primary);
        text-align: left;
    }

    .line-chart-wrapper {
        position: relative;
        width: 100%;
        height: 200px;
    }

    /* Bottom Grid */
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1.2fr;
        gap: 32px;
    }

    @media (max-width: 1024px) {
        .bottom-grid {
            grid-template-columns: 1fr;
        }
    }

    .doc-list-card {
        background-color: #ffffff;
        border: var(--card-border);
        border-radius: 24px;
        padding: 28px;
        box-shadow: var(--card-shadow);
        display: flex;
        flex-direction: column;
    }

    .doc-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
        margin-top: 10px;
        flex: 1;
    }

    .doc-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background-color: #f8fafc;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        transition: background-color 0.2s;
    }

    .doc-item:hover {
        background-color: #f1f5f9;
    }

    .doc-name {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-primary);
    }

    .doc-badge {
        background-color: rgba(227, 28, 61, 0.1);
        color: var(--accent-red);
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .view-all-link {
        align-self: center;
        margin-top: 20px;
        font-size: 13px;
        font-weight: 700;
        color: var(--accent-blue);
        text-decoration: none;
        transition: color 0.2s;
    }

    .view-all-link:hover {
        color: #1d4ed8;
        text-decoration: underline;
    }

    .quick-access-card {
        background-color: #ffffff;
        border: var(--card-border);
        border-radius: 24px;
        padding: 28px;
        box-shadow: var(--card-shadow);
    }

    .quick-access-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-top: 10px;
    }

    @media (max-width: 900px) {
        .quick-access-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 400px) {
        .quick-access-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .quick-access-item {
            padding: 14px 8px;
        }
    }

    .quick-access-item {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .quick-access-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        border-color: #cbd5e1;
        background-color: #f8fafc;
    }

    .quick-access-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .quick-access-icon.blue {
        background-color: rgba(30, 64, 175, 0.08);
        color: var(--accent-blue);
    }

    .quick-access-icon.red {
        background-color: rgba(227, 28, 61, 0.08);
        color: var(--accent-red);
    }

    .quick-access-name {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-primary);
        text-align: center;
        line-height: 1.3;
    }

    /* Footer */
    .dashboard-footer {
        padding: 24px 32px;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-secondary);
        background-color: #ffffff;
        margin-top: auto;
    }

    /* Custom scrollbars for side menu */
    .sidebar-menu::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-menu::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-menu::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
    }
    .sidebar-menu::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.2);
    }
</style>
</head>
<body>

<!-- Mobile overlay (tap to close sidebar) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div class="logo-text">
                    <span class="logo-title">CUP<span>.</span></span>
                    <span class="logo-subtitle">Preuniversitario</span>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="active">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Gestión Académica</div>
            <a href="{{ route('admin.carreras') }}">
                <i class="fa-solid fa-university"></i>
                <span>Carreras y Cupos</span>
            </a>
            <a href="{{ route('admin.grupos') }}">
                <i class="fa-solid fa-users-rectangle"></i>
                <span>Grupos</span>
            </a>
            <a href="{{ route('admin.aulas') }}">
                <i class="fa-solid fa-door-open"></i>
                <span>Aulas</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-book-open"></i>
                <span>Materias</span>
            </a>
            <a href="{{ route('admin.preferencias_cup') }}">
                <i class="fa-solid fa-star"></i>
                <span>Preferencias CUP</span>
            </a>

            <div class="sidebar-section-title">Inscripción y Doc.</div>
            <a href="{{ route('postulantes') }}">
                <i class="fa-solid fa-user-graduate"></i>
                <span>Postulantes</span>
            </a>
            <a href="{{ route('postulantes') }}">
                <i class="fa-solid fa-file-signature"></i>
                <span>Inscripciones</span>
            </a>
            <a href="{{ route('documentos.index') }}">
                <i class="fa-solid fa-folder-open"></i>
                <span>Documentos</span>
            </a>
            <a href="{{ route('admin.estado_postulante') }}">
                <i class="fa-solid fa-user-check"></i>
                <span>Estado del Postulante</span>
            </a>

            <div class="sidebar-section-title">Seguridad y Auditoría</div>
            <a href="{{ route('usuarios.roles') }}">
                <i class="fa-solid fa-user-shield"></i>
                <span>Usuarios y Roles</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-history"></i>
                <span>Bitácora</span>
            </a>

            <div class="sidebar-section-title">Logística y Reportes</div>
            <a href="{{ route('admin.docentes') }}">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Docentes</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-chart-line"></i>
                <span>Reportes</span>
            </a>

            <div style="border-t: 1px solid rgba(255,255,255,0.05); margin: 20px 0 10px 0;"></div>
            <a href="#">
                <i class="fa-solid fa-cog"></i>
                <span>Configuración</span>
            </a>
            <a href="#" style="color: #f87171;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-sign-out-alt" style="color: #f87171;"></i>
                <span>Cerrar Sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <!-- Hamburger: toggles sidebar on mobile -->
                <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <span class="page-title">Dashboard</span>
            </div>
            
            <div class="topbar-right">
                <button class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge">5</span>
                </button>
                
                <div class="profile-pill">
                    <div class="profile-avatar">SA</div>
                    <div class="profile-info">
                        <span class="profile-name">Super Administrador</span>
                        <span class="profile-role">Administrador</span>
                    </div>
                    <i class="fa-solid fa-chevron-down profile-chevron"></i>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="content-body">
            <!-- Welcome Header -->
            <div class="welcome-section">
                <div class="welcome-left">
                    <h2>Bienvenido, {{ Auth::user()->persona->nombre ?? 'Administrador' }}</h2>
                    <p>Sistema de Inscripción al CUP Preuniversitario</p>
                </div>
                
                <div class="date-badge">
                    <i class="fa-regular fa-calendar"></i>
                    <div class="date-info">
                        <span class="date-primary">{{ now()->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}</span>
                        <span class="date-secondary">{{ now()->locale('es')->isoFormat('dddd, h:mm A') }}</span>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-icon-wrapper blue">
                        <i class="fa-solid fa-user-group"></i>
                    </div>
                    <div class="kpi-details">
                        <span class="kpi-title">Postulantes Registrados</span>
                        <span class="kpi-value">{{ number_format($totalPostulantes) }}</span>
                        <span class="kpi-trend"><i class="fa-solid fa-database"></i> Dato en tiempo real</span>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon-wrapper red">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="kpi-details">
                        <span class="kpi-title">Inscripciones Activas</span>
                        <span class="kpi-value">{{ number_format($inscripcionesActivas) }}</span>
                        <span class="kpi-trend"><i class="fa-solid fa-database"></i> Dato en tiempo real</span>
                    </div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-icon-wrapper blue">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="kpi-details">
                        <span class="kpi-title">Pagos Completados</span>
                        <span class="kpi-value">{{ number_format($pagosCompletados) }}</span>
                        <span class="kpi-trend"><i class="fa-solid fa-database"></i> Dato en tiempo real</span>
                    </div>
                </div>

                <a href="{{ route('admin.grupos') }}" class="kpi-card block" style="text-decoration: none;">
                    <div class="kpi-icon-wrapper red">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="kpi-details">
                        <span class="kpi-title">Grupos Activos</span>
                        <span class="kpi-value">{{ number_format($gruposActivos) }}</span>
                        <span class="kpi-trend"><i class="fa-solid fa-database"></i> Dato en tiempo real</span>
                    </div>
                </a>
            </div>

            <!-- Charts Row -->
            <div class="charts-row">
                <!-- Left: Donut Chart -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <span class="chart-card-title">Inscripciones por Estado</span>
                    </div>
                    <div class="donut-container">
                        <div class="donut-chart-wrapper">
                            <canvas id="donutChart"></canvas>
                        </div>
                        <div class="donut-labels">
                            <div class="donut-label-item">
                                <div class="donut-label-left">
                                    <span class="donut-color-dot blue"></span>
                                    <span>Inscritos</span>
                                </div>
                                <span class="donut-label-value">{{ $donutData['activos'] }}</span>
                            </div>
                            <div class="donut-label-item">
                                <div class="donut-label-left">
                                    <span class="donut-color-dot red"></span>
                                    <span>En Proceso</span>
                                </div>
                                <span class="donut-label-value">{{ $donutData['pendiente'] }}</span>
                            </div>
                            <div class="donut-label-item">
                                <div class="donut-label-left">
                                    <span class="donut-color-dot dark-blue"></span>
                                    <span>Documentos Pendientes</span>
                                </div>
                                <span class="donut-label-value">{{ $donutData['docsCount'] }}</span>
                            </div>
                            <div class="donut-label-item">
                                <div class="donut-label-left">
                                    <span class="donut-color-dot grey"></span>
                                    <span>Inactivos</span>
                                </div>
                                <span class="donut-label-value">{{ $donutData['inactivo'] }}</span>
                            </div>
                            <div class="donut-total">
                                Total: {{ number_format($donutData['total']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Line Chart -->
                <div class="chart-card">
                    <div class="chart-card-header">
                        <span class="chart-card-title">Inscripciones por Mes</span>
                    </div>
                    <div class="line-chart-wrapper">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Bottom Grid -->
            <div class="bottom-grid">
                <!-- Left: Pending Docs -->
                <div class="doc-list-card">
                    <div class="chart-card-header">
                        <span class="chart-card-title">Documentos Pendientes</span>
                    </div>
                    <div class="doc-list">
                        @forelse($documentosPendientes as $doc)
                        <div class="doc-item">
                            <span class="doc-name">{{ $doc->tipo_documento }}</span>
                            <span class="doc-badge">{{ $doc->cantidad }}</span>
                        </div>
                        @empty
                        <div class="doc-item">
                            <span class="doc-name" style="color:#94a3b8;">Sin documentos pendientes</span>
                            <span class="doc-badge" style="background:rgba(148,163,184,0.1);color:#94a3b8;">0</span>
                        </div>
                        @endforelse
                    </div>
                    <a href="{{ route('documentos.index') }}" class="view-all-link">Ver todos</a>
                </div>

                <!-- Right: Quick Access -->
                <div class="quick-access-card">
                    <div class="chart-card-header">
                        <span class="chart-card-title">Accesos Rápidos</span>
                    </div>
                    <div class="quick-access-grid">
                        <a href="{{ route('postulantes') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon blue"><i class="fa-solid fa-user-plus"></i></div>
                            <span class="quick-access-name">Postulantes</span>
                        </a>
                        <a href="{{ route('admin.aulas') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon red"><i class="fa-solid fa-school"></i></div>
                            <span class="quick-access-name">Aulas</span>
                        </a>
                        <a href="{{ route('admin.preferencias_cup') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon blue"><i class="fa-solid fa-star"></i></div>
                            <span class="quick-access-name">Preferencias CUP</span>
                        </a>
                        <a href="{{ route('admin.docentes') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon blue"><i class="fa-solid fa-chalkboard-user"></i></div>
                            <span class="quick-access-name">Docentes</span>
                        </a>
                        <a href="{{ route('admin.estado_postulante') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon red"><i class="fa-solid fa-user-check"></i></div>
                            <span class="quick-access-name">Estado Postulante</span>
                        </a>
                        <a href="{{ route('admin.grupos') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon blue"><i class="fa-solid fa-people-group"></i></div>
                            <span class="quick-access-name">Grupos</span>
                        </a>
                        <a href="{{ route('usuarios.roles') }}" class="quick-access-item" style="text-decoration:none;">
                            <div class="quick-access-icon blue"><i class="fa-solid fa-list-check"></i></div>
                            <span class="quick-access-name">Usuarios y Roles</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="dashboard-footer">
            © 2024 CUP Preuniversitario. Todos los derechos reservados.
        </div>
    </div>
</div>

<script>
    // Premium Donut Chart Configuration
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($donutData['labels']) !!},
            datasets: [{
                data: {!! json_encode($donutData['values']) !!},
                backgroundColor: {!! json_encode($donutData['colors']) !!},
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ` ${context.label}: ${context.raw}`;
                        }
                    }
                }
            },
            cutout: '72%'
        }
    });

    // Premium Line Chart Configuration
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    
    // Create gradient fill
    const blueGradient = lineCtx.createLinearGradient(0, 0, 0, 200);
    blueGradient.addColorStop(0, 'rgba(30, 64, 175, 0.3)');
    blueGradient.addColorStop(1, 'rgba(30, 64, 175, 0)');

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($lineData['labels']) !!},
            datasets: [{
                label: 'Inscripciones',
                data: {!! json_encode($lineData['values']) !!},
                borderColor: '#1e40af',
                borderWidth: 4,
                pointBackgroundColor: '#1e40af',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8,
                fill: true,
                backgroundColor: blueGradient,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            family: 'Outfit',
                            size: 11,
                            weight: '600'
                        },
                        color: '#64748b'
                    }
                },
                y: {
                    min: 0,
                    ticks: {
                        stepSize: 250,
                        font: {
                            family: 'Outfit',
                            size: 11,
                            weight: '600'
                        },
                        color: '#64748b'
                    },
                    grid: {
                        color: '#f1f5f9'
                    }
                }
            }
        }
    });

    /* ── Responsive sidebar toggle ────────────────────────── */
    const sidebar        = document.querySelector('.sidebar');
    const overlay        = document.getElementById('sidebarOverlay');
    const menuToggle     = document.getElementById('menuToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    menuToggle.addEventListener('click', function () {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    // Close sidebar on resize to desktop
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
</script>
</body>
</html>