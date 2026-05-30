<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Docentes - CUP Sistema de Admisión</title>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --bg-main: #f8fafc;
        --sidebar-bg: #071426;
        --sidebar-active: #e31c3d;
        --sidebar-hover: rgba(255, 255, 255, 0.07);
        --text-primary: #111827;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --border: #e5e7eb;
        --card-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        --radius: 14px;
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

    .logo-container {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-icon {
        width: 42px;
        height: 42px;
        background: var(--sidebar-active);
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-size: 18px;
        box-shadow: 0 4px 10px rgba(227, 28, 61, 0.3);
    }

    .logo-title {
        display: block;
        font-size: 26px;
        font-weight: 800;
        line-height: 1;
    }

    .logo-subtitle {
        font-size: 10px;
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
        box-shadow: 0 8px 18px rgba(227, 28, 61, 0.25);
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

    .notification-btn {
        border: none;
        background: transparent;
        position: relative;
        font-size: 19px;
        cursor: pointer;
        color: #111827;
    }

    .notification-badge {
        position: absolute;
        top: -7px;
        right: -7px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--sidebar-active);
        color: #fff;
        font-size: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border: 2px solid #ffffff;
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
        background: #f1f5f9;
        color: #111827;
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

    .card {
        background: #fff;
        border: 1px solid #eef2f7;
        border-radius: var(--radius);
        box-shadow: var(--card-shadow);
    }

    .form-card {
        padding: 28px;
        margin-bottom: 26px;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .form-header h2 {
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .form-header p {
        font-size: 14px;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .info-box {
        background: #fff7ed;
        color: #c2410c;
        border-radius: 8px;
        padding: 16px 18px;
        display: flex;
        gap: 12px;
        align-items: center;
        font-size: 13px;
        font-weight: 600;
        max-width: 480px;
        border: 1px solid #ffedd5;
    }

    .info-box i {
        color: #f97316;
        font-size: 18px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.double {
        grid-column: span 2;
    }

    .form-group.full {
        grid-column: span 3;
    }

    label {
        font-size: 14px;
        font-weight: 700;
        color: #111827;
    }

    .required {
        color: var(--sidebar-active);
    }

    input, select, textarea {
        width: 100%;
        border: 1px solid #dfe3ea;
        border-radius: 7px;
        padding: 13px 16px;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        color: #111827;
        outline: none;
        background: #fff;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    textarea {
        min-height: 95px;
        resize: vertical;
    }

    input:focus, select:focus, textarea:focus {
        border-color: var(--sidebar-active);
        box-shadow: 0 0 0 3px rgba(227, 28, 61, 0.08);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 18px;
    }

    .btn {
        border: none;
        border-radius: 7px;
        padding: 13px 20px;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        transition: all .2s ease;
    }

    .btn-cancel {
        background: #fff;
        color: #111827;
        border: 1px solid #cfd6df;
    }

    .btn-cancel:hover {
        background: #f8fafc;
    }

    .btn-save {
        background: var(--sidebar-active);
        color: #fff;
        box-shadow: 0 8px 16px rgba(227, 28, 61, 0.18);
    }

    .btn-save:hover {
        background: #c91533;
    }

    .table-card {
        padding: 24px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 20px;
    }

    .table-header h2 {
        font-size: 18px;
        font-weight: 800;
        color: #111827;
    }

    .table-tools {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 14px;
    }

    .search-box input {
        width: 270px;
        padding-left: 40px;
    }

    .btn-filter {
        background: #fff;
        color: #111827;
        border: 1px solid #dfe3ea;
        padding: 12px 18px;
        border-radius: 7px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead th {
        text-align: left;
        color: #111827;
        font-weight: 800;
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
    }

    tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #e5e7eb;
        color: #111827;
        font-weight: 500;
    }

    tbody tr:hover {
        background: #fafafa;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        gap: 6px;
    }

    .badge::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge-active {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-active::before {
        background: #10b981;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-inactive::before {
        background: #ef4444;
    }

    .actions {
        display: flex;
        gap: 14px;
        align-items: center;
    }

    .action-edit {
        color: #2563eb;
        cursor: pointer;
        background: none;
        border: none;
        font-size: 16px;
    }

    .action-delete {
        color: var(--sidebar-active);
        cursor: pointer;
        background: none;
        border: none;
        font-size: 16px;
    }

    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 0 0;
        font-size: 14px;
        font-weight: 600;
    }

    .pagination {
        display: flex;
        gap: 6px;
    }

    .page-btn {
        width: 38px;
        height: 38px;
        border-radius: 7px;
        border: 1px solid #dfe3ea;
        background: #fff;
        cursor: pointer;
        font-weight: 700;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .page-btn.active {
        background: var(--sidebar-active);
        color: #fff;
        border-color: var(--sidebar-active);
    }

    .page-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        z-index: 90;
    }

    @media (max-width: 900px) {
        .sidebar {
            transform: translateX(-100%);
            transition: .3s ease;
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay.active {
            display: block;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.double, .form-group.full {
            grid-column: span 1;
        }

        .form-header {
            flex-direction: column;
            gap: 10px;
        }

        .info-box {
            max-width: 100%;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .table-tools {
            flex-direction: column;
            align-items: stretch;
        }

        .search-box input {
            width: 100%;
        }

        table {
            min-width: 900px;
        }
    }
</style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-container">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <span class="logo-title">CUP.</span>
                    <span class="logo-subtitle">Sistema de Admisión</span>
                </div>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Gestión Académica</div>

            <a href="{{ route('admin.carreras') }}">
                <i class="fa-solid fa-hotel"></i>
                <span>Carreras y Cupos</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-address-card"></i>
                <span>Grupos</span>
            </a>

            <a href="{{ route('admin.aulas') }}">
                <i class="fa-solid fa-school"></i>
                <span>Aulas</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-calendar-days"></i>
                <span>Horarios</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-book"></i>
                <span>Materias</span>
            </a>

            <a href="{{ route('admin.preferencias_cup') }}">
                <i class="fa-solid fa-star"></i>
                <span>Preferencias CUP</span>
            </a>

            <div class="sidebar-section-title">Inscripción y Doc.</div>

            <a href="{{ route('postulantes') }}">
                <i class="fa-solid fa-user"></i>
                <span>Postulantes</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Inscripciones</span>
            </a>

            <a href="{{ route('documentos.index') }}">
                <i class="fa-solid fa-folder"></i>
                <span>Documentos</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-folder-open"></i>
                <span>Requisitos</span>
            </a>

            <a href="{{ route('postulante.estado') }}">
                <i class="fa-solid fa-user-check"></i>
                <span>Estado del Postulante</span>
            </a>

            <div class="sidebar-section-title">Logística</div>

            <a href="{{ route('admin.docentes') }}" class="active">
                <i class="fa-solid fa-chalkboard-user"></i>
                <span>Docente</span>
            </a>

            <div class="sidebar-section-title">Usuarios y Seguridad</div>

            <a href="{{ route('usuarios.roles') }}">
                <i class="fa-solid fa-users"></i>
                <span>Usuarios y Roles</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>Bitácora</span>
            </a>

            <div class="sidebar-section-title">Configuración</div>

            <a href="#">
                <i class="fa-solid fa-list-check"></i>
                <span>Parámetros</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-gear"></i>
                <span>Configuración</span>
            </a>

            <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Cerrar Sesión</span>
            </a>
        </nav>
    </aside>

    <main class="main-content">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="topbar-right">
                <button class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>

                <div class="profile-pill">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <span>Administrador</span>
                    <i class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <section class="content-body">

            @if(session('success'))
                <div style="background-color: #dcfce7; color: #15803d; padding: 14px 20px; border-radius: var(--radius); margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; border: 1px solid #bbf7d0;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div style="background-color: #fee2e2; color: #dc2626; padding: 14px 20px; border-radius: var(--radius); margin-bottom: 20px; font-weight: 600; border: 1px solid #fecaca;">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="page-header">
                <h1>Docentes</h1>
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.docentes') }}">Docentes</a>
                    <span>/</span>
                    <span>Nuevo Docente</span>
                </div>
            </div>

            <!-- REGISTRATION FORM CARD -->
            <div class="card form-card">
                <div class="form-header">
                    <div>
                        <h2 id="form-title">Registrar Nuevo Docente</h2>
                        <p>Asigne el rol de docente a una persona existente y configure sus detalles.</p>
                    </div>

                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Los docentes registrados podrán ser asignados a grupos de estudio y materias del CUP.</span>
                    </div>
                </div>

                <form id="form-action" action="{{ route('admin.docentes.store') }}" method="POST">
                    @csrf
                    <div id="method-field"></div>

                    <div class="form-grid">
                        <div class="form-group" id="persona-group">
                            <label for="Id_persona">Persona <span class="required">*</span></label>
                            <select name="Id_persona" id="Id_persona" required>
                                <option value="">Seleccione una persona</option>
                                @foreach($personas as $per)
                                    <option value="{{ $per->id_persona }}">{{ $per->ci }} - {{ $per->nombre }} {{ $per->apellido }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="anio_servicio">Años de Servicio <span class="required">*</span></label>
                            <input type="number" name="anio_servicio" id="anio_servicio" min="0" placeholder="Ej: 5" required>
                        </div>

                        <div class="form-group">
                            <label for="id_especialidad">Especialidad <span class="required">*</span></label>
                            <select name="id_especialidad" id="id_especialidad" required>
                                <option value="">Seleccione especialidad</option>
                                @foreach($especialidades as $esp)
                                    <option value="{{ $esp->id_especialidad }}">{{ $esp->nombre_especialidad }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado</label>
                            <select name="estado" id="estado">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" id="cancel-btn" style="display:none;" onclick="cancelEdit()">Cancelar</button>
                        <button type="submit" class="btn btn-save" id="submit-btn">
                            <i class="fa-solid fa-floppy-disk"></i> Guardar Docente
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABLE CARD -->
            <div class="card table-card">
                <div class="table-header">
                    <h2>Docentes Registrados</h2>
                    <div class="table-tools">
                        <form action="{{ route('admin.docentes') }}" method="GET" class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar docente...">
                        </form>
                        <button class="btn-filter">
                            <i class="fa-solid fa-filter"></i> Filtrar
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>CI</th>
                                <th>Años Servicio</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($docentes as $key => $doc)
                            <tr>
                                <td>{{ $docentes->firstItem() + $key }}</td>
                                <td>{{ $doc->persona->nombre }} {{ $doc->persona->apellido }}</td>
                                <td>{{ $doc->persona->ci }}</td>
                                <td>{{ $doc->anio_servicio }}</td>
                                <td>
                                    @forelse($doc->especialidades as $esp)
                                        <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight:600;">{{ $esp->nombre_especialidad }}</span>
                                    @empty
                                        <span style="color: var(--text-muted);">Sin asignar</span>
                                    @endforelse
                                </td>
                                <td>
                                    <span class="badge {{ $doc->estado == 'Activo' ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $doc->estado }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button class="action-edit" title="Editar" onclick="editDocente({{ json_encode($doc) }})">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <form action="{{ route('admin.docentes.destroy', $doc->id_docente) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este docente?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-delete" title="Eliminar">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding: 20px; color: var(--text-secondary);">No se encontraron docentes registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <div>
                        Mostrando {{ $docentes->firstItem() ?? 0 }} a {{ $docentes->lastItem() ?? 0 }} de {{ $docentes->total() }} docentes
                    </div>
                    <div class="pagination">
                        @if ($docentes->onFirstPage())
                            <span class="page-btn disabled"><i class="fa-solid fa-angles-left"></i></span>
                            <span class="page-btn disabled"><i class="fa-solid fa-angle-left"></i></span>
                        @else
                            <a href="{{ $docentes->url(1) . (request('buscar') ? '&buscar='.request('buscar') : '') }}" class="page-btn"><i class="fa-solid fa-angles-left"></i></a>
                            <a href="{{ $docentes->previousPageUrl() . (request('buscar') ? '&buscar='.request('buscar') : '') }}" class="page-btn"><i class="fa-solid fa-angle-left"></i></a>
                        @endif

                        @foreach ($docentes->getUrlRange(max(1, $docentes->currentPage() - 2), min($docentes->lastPage(), $docentes->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url . (request('buscar') ? '&buscar='.request('buscar') : '') }}" class="page-btn {{ $page == $docentes->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        @if ($docentes->hasMorePages())
                            <a href="{{ $docentes->nextPageUrl() . (request('buscar') ? '&buscar='.request('buscar') : '') }}" class="page-btn"><i class="fa-solid fa-angle-right"></i></a>
                            <a href="{{ $docentes->url($docentes->lastPage()) . (request('buscar') ? '&buscar='.request('buscar') : '') }}" class="page-btn"><i class="fa-solid fa-angles-right"></i></a>
                        @else
                            <span class="page-btn disabled"><i class="fa-solid fa-angle-right"></i></span>
                            <span class="page-btn disabled"><i class="fa-solid fa-angles-right"></i></span>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </main>

</div>

<script>
    // Toggle sidebar on mobile
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (menuToggle) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            sidebarOverlay.classList.toggle('active');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            sidebarOverlay.classList.remove('active');
        });
    }

    // JS Edit Handler
    function editDocente(doc) {
        document.getElementById('form-title').innerText = 'Editar Docente: ' + doc.persona.nombre + ' ' + doc.persona.apellido;
        document.getElementById('form-action').action = '/admin/docentes/' + doc.id_docente;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        document.getElementById('persona-group').style.display = 'none';
        document.getElementById('anio_servicio').value = doc.anio_servicio;
        document.getElementById('estado').value = doc.estado;
        if (doc.especialidades && doc.especialidades.length > 0) {
            document.getElementById('id_especialidad').value = doc.especialidades[0].id_especialidad;
        } else {
            document.getElementById('id_especialidad').value = '';
        }
        document.getElementById('submit-btn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Cambios';
        document.getElementById('cancel-btn').style.display = 'inline-flex';
        
        window.scrollTo({top: 0, behavior: 'smooth'});
    }

    function cancelEdit() {
        document.getElementById('form-title').innerText = 'Registrar Nuevo Docente';
        document.getElementById('form-action').action = '/admin/docentes';
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('persona-group').style.display = 'flex';
        document.getElementById('Id_persona').value = '';
        document.getElementById('anio_servicio').value = '';
        document.getElementById('id_especialidad').value = '';
        document.getElementById('estado').value = 'Activo';
        document.getElementById('submit-btn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Guardar Docente';
        document.getElementById('cancel-btn').style.display = 'none';
    }
</script>

</body>
</html>
