<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Documentos - CUP Sistema de Admisión</title>

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
        color: #9ca3af;
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
        margin-bottom: 15px;
    }

    .form-header p {
        font-size: 14px;
        color: #111827;
        font-weight: 500;
    }

    .info-box {
        background: #fff3e8;
        color: #111827;
        border-radius: 8px;
        padding: 16px 18px;
        display: flex;
        gap: 12px;
        align-items: center;
        font-size: 13px;
        font-weight: 600;
        max-width: 330px;
    }

    .info-box i {
        color: #f97316;
        font-size: 18px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.full {
        grid-column: span 2;
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
        padding: 24px 24px 0;
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
        padding: 7px 16px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 700;
    }

    .badge-yes {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-no {
        background: #fee2e2;
        color: #dc2626;
    }

    .badge-active {
        background: #dcfce7;
        color: #15803d;
    }

    .actions {
        display: flex;
        gap: 18px;
        align-items: center;
    }

    .action-edit {
        color: #334155;
        cursor: pointer;
    }

    .action-delete {
        color: var(--sidebar-active);
        cursor: pointer;
    }

    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 0 24px;
        font-size: 14px;
        font-weight: 600;
    }

    .pagination {
        display: flex;
        gap: 8px;
    }

    .page-btn {
        width: 42px;
        height: 40px;
        border-radius: 7px;
        border: 1px solid #dfe3ea;
        background: #fff;
        cursor: pointer;
        font-weight: 700;
        color: #334155;
    }

    .page-btn.active {
        background: var(--sidebar-active);
        color: #fff;
        border-color: var(--sidebar-active);
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

        .form-group.full {
            grid-column: span 1;
        }

        .form-header {
            flex-direction: column;
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

        .table-responsive {
            overflow-x: auto;
        }

        table {
            min-width: 900px;
        }

        .content-body {
            padding: 20px;
        }
    }
</style>
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="dashboard-container">

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

            <a href="#">
                <i class="fa-solid fa-table-cells"></i>
                <span>Carreras y Cupos</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-address-card"></i>
                <span>Grupos</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-door-open"></i>
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

            <a href="#">
                <i class="fa-solid fa-star"></i>
                <span>Docentes</span>
            </a>

            <div class="sidebar-section-title">Gestión de Admisiones</div>

            <a href="{{ route('postulantes') }}">
                <i class="fa-solid fa-user"></i>
                <span>Postulantes</span>
            </a>

            <a href="{{ route('postulantes') }}">
                <i class="fa-solid fa-clipboard-check"></i>
                <span>Inscripciones</span>
            </a>

            <a href="{{ route('documentos.index') }}" class="active">
                <i class="fa-solid fa-folder"></i>
                <span>Documentos</span>
            </a>

            <a href="#">
                <i class="fa-solid fa-folder-open"></i>
                <span>Requisitos</span>
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
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </aside>

    <main class="main-content">

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
                <h1>Documentos</h1>
                <div class="breadcrumb">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('documentos.index') }}">Documentos</a>
                    <span>/</span>
                    <span>Nuevo Documento</span>
                </div>
            </div>

            <div class="card form-card">
                <div class="form-header">
                    <div>
                        <h2>Registrar Nuevo Documento (Requisito de Admisión)</h2>
                        <p>Complete la información para definir un nuevo documento que será solicitado a los postulantes.</p>
                    </div>

                    <div class="info-box">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Este documento será solicitado a los postulantes durante la inscripción.</span>
                    </div>
                </div>

                <form id="documentoForm" action="{{ route('documentos.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <input type="hidden" id="documentoId" name="id" value="">

                    <div class="form-grid">

                        <div class="form-group">
                            <label>Nombre del Documento <span class="required">*</span></label>
                            <input type="text" id="inputNombre" name="nombre" placeholder="Ej: Certificado de Estudios" required>
                        </div>

                        <div class="form-group">
                            <label>Descripción (Opcional)</label>
                            <input type="text" id="inputDescripcion" name="descripcion" placeholder="Descripción breve del documento (opcional)">
                        </div>

                        <div class="form-group">
                            <label>Categoría <span class="required">*</span></label>
                            <select id="selectCategoria" name="categoria" required>
                                <option value="">Seleccione una categoría</option>
                                <option value="Identificación">Identificación</option>
                                <option value="Académico">Académico</option>
                                <option value="Personal">Personal</option>
                                <option value="Financiero">Financiero</option>
                                <option value="Legal">Legal</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>¿Es Obligatorio? <span class="required">*</span></label>
                            <select id="selectObligatorio" name="obligatorio" required>
                                <option value="">Seleccione una opción</option>
                                <option value="Sí">Sí</option>
                                <option value="No">No</option>
                            </select>
                        </div>

                        <div class="form-group full">
                            <label>Observaciones (Opcional)</label>
                            <textarea id="inputObservaciones" name="observaciones" placeholder="Información adicional para el administrador o para el postulante sobre este documento..."></textarea>
                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-cancel" onclick="resetForm()">Cancelar</button>
                        <button type="submit" class="btn btn-save">
                            <i class="fa-regular fa-floppy-disk"></i>
                            <span id="submitBtnText">Guardar Documento</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card table-card">
                <div class="table-header">
                    <h2>Lista de Documentos (Requisitos de Admisión)</h2>

                    <div class="table-tools">
                        <div class="search-box">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" placeholder="Buscar documento...">
                        </div>

                        <button class="btn btn-filter">
                            <i class="fa-solid fa-filter"></i>
                            Filtrar
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre del Documento</th>
                                <th>Categoría</th>
                                <th>Obligatorio</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($documentos as $index => $doc)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $doc['nombre'] }}</td>
                                <td>{{ $doc['categoria'] }}</td>
                                <td>
                                    <span class="badge {{ $doc['obligatorio'] === 'Sí' ? 'badge-yes' : 'badge-no' }}">
                                        {{ $doc['obligatorio'] }}
                                    </span>
                                </td>
                                <td>{{ $doc['descripcion'] }}</td>
                                <td><span class="badge badge-active">{{ $doc['estado'] }}</span></td>
                                <td>
                                    <div class="actions">
                                        <i class="fa-regular fa-pen-to-square action-edit" onclick='editarDocumento(@json($doc))'></i>
                                        <form action="{{ route('documentos.destroy', $doc['id']) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este documento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none; border:none; color:var(--sidebar-active); cursor:pointer; padding:0;">
                                                <i class="fa-regular fa-trash-can action-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span>Mostrando 1 a 6 de 6 documentos</span>

                    <div class="pagination">
                        <button class="page-btn"><i class="fa-solid fa-angles-left"></i></button>
                        <button class="page-btn"><i class="fa-solid fa-angle-left"></i></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn"><i class="fa-solid fa-angle-right"></i></button>
                        <button class="page-btn"><i class="fa-solid fa-angles-right"></i></button>
                    </div>
                </div>
            </div>

        </section>

    </main>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menuToggle = document.getElementById('menuToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
    }

    menuToggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth > 900) {
            closeSidebar();
        }
    });

    // Form editing logic
    function editarDocumento(doc) {
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('documentoForm').action = "/documentos/" + doc.id;
        document.getElementById('documentoId').value = doc.id;
        document.getElementById('inputNombre').value = doc.nombre;
        document.getElementById('inputDescripcion').value = doc.descripcion || '';
        document.getElementById('selectCategoria').value = doc.categoria;
        document.getElementById('selectObligatorio').value = doc.obligatorio;
        document.getElementById('inputObservaciones').value = doc.observaciones || '';

        document.getElementById('submitBtnText').textContent = 'Actualizar Documento';
        document.querySelector('.form-header h2').textContent = 'Editar Documento (Requisito de Admisión)';

        // Scroll smooth to form
        document.querySelector('.form-card').scrollIntoView({ behavior: 'smooth' });
    }

    function resetForm() {
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('documentoForm').action = "{{ route('documentos.store') }}";
        document.getElementById('documentoId').value = '';
        document.getElementById('documentoForm').reset();

        document.getElementById('submitBtnText').textContent = 'Guardar Documento';
        document.querySelector('.form-header h2').textContent = 'Registrar Nuevo Documento (Requisito de Admisión)';
    }

    // Live search filter
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
</script>

</body>
</html>