<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Postulantes - CUP UAGRM</title>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
    :root {
        --bg-main: #f8fafc;
        --sidebar-bg: #07153a;
        --sidebar-active: #0052cc;
        --sidebar-hover: rgba(0, 82, 204, 0.18);
        --text-primary: #111827;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --accent-blue: #0052cc;
        --accent-red: #e31c3d;
        --border: #e5e7eb;
        --card-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Outfit', sans-serif;
        background: var(--bg-main);
        color: var(--text-primary);
        min-height: 100vh;
    }

    .layout {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 230px;
        background: var(--sidebar-bg);
        color: white;
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        z-index: 100;
    }

    .sidebar-header {
        height: 70px;
        display: flex;
        align-items: center;
        padding: 0 24px;
        background: white;
        border-right: 1px solid var(--border);
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #0b1d4a;
    }

    .brand-icon {
        width: 34px;
        height: 34px;
        border: 5px solid var(--accent-red);
        transform: rotate(45deg);
        border-radius: 3px;
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

    .brand-text {
        display: flex;
        flex-direction: column;
        line-height: 1;
    }

    .brand-title {
        font-size: 28px;
        font-weight: 900;
        letter-spacing: 1px;
    }

    .brand-subtitle {
        font-size: 8px;
        font-weight: 800;
        color: #64748b;
        margin-top: 3px;
        letter-spacing: .4px;
    }

    .sidebar-menu {
        padding: 20px 10px;
    }

    .menu-section {
        font-size: 10px;
        color: #8ea3c7;
        font-weight: 800;
        text-transform: uppercase;
        margin: 18px 14px 8px;
        letter-spacing: .8px;
    }

    .sidebar-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        text-decoration: none;
        color: rgba(255,255,255,.78);
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 4px;
        transition: all .2s;
    }

    .sidebar-menu a i {
        width: 18px;
        text-align: center;
        font-size: 14px;
    }

    .sidebar-menu a:hover,
    .sidebar-menu a.active {
        background: var(--sidebar-active);
        color: white;
    }

    .main {
        margin-left: 230px;
        width: calc(100% - 230px);
        min-height: 100vh;
    }

    .topbar {
        height: 70px;
        background: white;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 28px;
        position: sticky;
        top: 0;
        z-index: 50;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
    }

    .topbar-left {
        display: flex;
        align-items: center;
        gap: 28px;
    }

    .menu-btn {
        border: none;
        background: none;
        color: #0b1d4a;
        font-size: 20px;
        cursor: pointer;
    }

    .topbar-right {
        display: flex;
        align-items: center;
        gap: 24px;
    }

    .notification {
        border: none;
        background: none;
        color: #0b1d4a;
        font-size: 18px;
        position: relative;
    }

    .notification span {
        position: absolute;
        top: -8px;
        right: -8px;
        background: red;
        color: white;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        font-size: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .profile-avatar {
        width: 38px;
        height: 38px;
        background: var(--accent-blue);
        color: white;
        border-radius: 50%;
        display: grid;
        place-items: center;
        font-size: 18px;
    }

    .profile-name {
        font-size: 13px;
        font-weight: 800;
        color: #0b1d4a;
    }

    .profile-role {
        font-size: 11px;
        color: var(--text-secondary);
        font-weight: 600;
    }

    .content {
        padding: 28px;
    }

    .page-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .page-head h1 {
        font-size: 28px;
        color: #0b1d4a;
        font-weight: 900;
    }

    .page-head p {
        margin-top: 5px;
        color: #475569;
        font-size: 14px;
        font-weight: 500;
    }

    .btn-primary {
        height: 42px;
        background: #0052cc;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0 18px;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        box-shadow: 0 6px 14px rgba(0,82,204,.18);
    }

    .tools {
        display: grid;
        grid-template-columns: 1fr 190px;
        gap: 14px;
        max-width: 560px;
        margin-bottom: 18px;
    }

    .tool-box {
        height: 44px;
        background: white;
        border: 1px solid var(--border);
        border-radius: 6px;
        display: flex;
        align-items: center;
        padding: 0 14px;
        gap: 10px;
    }

    .tool-box input,
    .tool-box select {
        border: none;
        outline: none;
        width: 100%;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        color: #334155;
        background: transparent;
    }

    .page-grid {
        display: grid;
        grid-template-columns: 1.1fr .92fr;
        gap: 20px;
        align-items: start;
    }

    .card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        box-shadow: var(--card-shadow);
    }

    .list-card {
        padding: 22px;
    }

    .card-title {
        font-size: 18px;
        color: #111827;
        font-weight: 900;
        margin-bottom: 18px;
    }

    .table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        text-align: left;
        font-size: 11px;
        color: #111827;
        font-weight: 900;
        padding: 12px 10px;
        border-bottom: 1px solid var(--border);
    }

    td {
        padding: 13px 10px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
        color: #111827;
        font-weight: 600;
    }

    tr {
        cursor: pointer;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    tbody tr.selected {
        background: #eef4ff;
    }

    .badge {
        padding: 5px 9px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 800;
        display: inline-flex;
    }

    .badge-inscrito {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-pendiente {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-revision {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .list-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 18px;
        font-size: 12px;
        color: #334155;
        font-weight: 700;
    }

    .pagination {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .page-btn {
        width: 34px;
        height: 34px;
        border: 1px solid var(--border);
        background: white;
        border-radius: 50%;
        font-weight: 800;
        cursor: pointer;
    }

    .page-btn.active {
        background: var(--accent-blue);
        color: white;
        border-color: var(--accent-blue);
    }

    .detail-card {
        padding: 0;
        overflow: hidden;
    }

    .detail-header {
        padding: 20px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
    }

    .detail-header h3 {
        font-size: 18px;
        font-weight: 900;
        color: #111827;
    }

    .close-btn {
        border: none;
        background: transparent;
        font-size: 20px;
        color: #0f172a;
        cursor: pointer;
    }

    .student-profile {
        padding: 22px;
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .student-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: var(--accent-blue);
        color: white;
        display: grid;
        place-items: center;
        font-size: 30px;
    }

    .student-name {
        font-size: 17px;
        font-weight: 900;
        color: #111827;
    }

    .student-info {
        font-size: 12px;
        color: #475569;
        margin-top: 4px;
        font-weight: 600;
    }

    .tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        padding: 0 22px;
        gap: 24px;
    }

    .tab {
        border: none;
        background: none;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 13px;
        color: #64748b;
        padding: 16px 0;
        border-bottom: 3px solid transparent;
        cursor: pointer;
    }

    .tab.active {
        color: var(--accent-blue);
        border-bottom-color: var(--accent-blue);
    }

    .detail-body {
        padding: 28px 22px;
    }

    .detail-section-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
    }

    .detail-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #eff6ff;
        color: var(--accent-blue);
        display: grid;
        place-items: center;
    }

    .detail-section-head h4 {
        font-size: 17px;
        font-weight: 900;
        color: #111827;
    }

    .detail-section-head p {
        font-size: 12px;
        color: #64748b;
        margin-top: 4px;
        font-weight: 600;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 900;
        color: #111827;
        margin-bottom: 8px;
    }

    .required {
        color: red;
    }

    .form-group select {
        width: 100%;
        height: 46px;
        border: 1px solid #dbe2ea;
        border-radius: 6px;
        padding: 0 12px;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: #475569;
        outline: none;
        background: white;
    }

    .info-box {
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 700;
        border-radius: 6px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 10px 0 24px;
    }

    .inscribir-btn {
        width: 100%;
        height: 46px;
        border: none;
        border-radius: 6px;
        background: var(--accent-blue);
        color: white;
        font-family: 'Outfit', sans-serif;
        font-size: 14px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        cursor: pointer;
    }

    .note {
        text-align: center;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        margin-top: 12px;
    }

    @media (max-width: 1100px) {
        .page-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .main {
            margin-left: 0;
            width: 100%;
        }

        .tools {
            grid-template-columns: 1fr;
            max-width: 100%;
        }

        .content {
            padding: 18px;
        }

        .page-head {
            flex-direction: column;
            gap: 16px;
        }
    }
</style>
</head>

<body>

<div class="layout">
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
            <div class="menu-section">Inicio</div>
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>

            <div class="menu-section">Inscripciones</div>
            <a href="{{ route('postulantes') }}" class="active">
                <i class="fa-solid fa-user-group"></i>
                <span>Postulantes</span>
            </a>
            <a href="{{ route('postulantes') }}">
                <i class="fa-regular fa-clipboard"></i>
                <span>Inscripciones</span>
            </a>

            <div class="menu-section">Gestión Académica</div>
            <a href="#">
                <i class="fa-solid fa-building-columns"></i>
                <span>Sedes y Modalidades</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-graduation-cap"></i>
                <span>Carreras</span>
            </a>
            <a href="#">
                <i class="fa-regular fa-calendar-days"></i>
                <span>Cursos y Turnos</span>
            </a>
            <a href="#">
                <i class="fa-regular fa-file-lines"></i>
                <span>Requisitos</span>
            </a>

            <div class="menu-section">Configuración</div>
            <a href="{{ route('usuarios.roles') }}">
                <i class="fa-solid fa-user"></i>
                <span>Usuarios</span>
            </a>
            <a href="#">
                <i class="fa-solid fa-user-shield"></i>
                <span>Roles</span>
            </a>
            <a href="{{ route('documentos.index') }}">
                <i class="fa-regular fa-folder"></i>
                <span>Documentos</span>
            </a>

            <div class="menu-section">Ayuda</div>
            <a href="#">
                <i class="fa-regular fa-circle-question"></i>
                <span>Preguntas Frecuentes</span>
            </a>
            <a href="#" style="color: #f87171;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-sign-out-alt" style="color: #f87171;"></i>
                <span>Cerrar Sesión</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </aside>

    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-btn" id="menuToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

            <div class="topbar-right">
                <button class="notification">
                    <i class="fa-regular fa-bell"></i>
                    <span>0</span>
                </button>

                <div class="profile">
                    <div class="profile-avatar">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <div class="profile-name">Administrador</div>
                        <div class="profile-role">Administrador</div>
                    </div>
                    <i class="fa-solid fa-chevron-down" style="font-size:12px;color:#64748b;"></i>
                </div>
            </div>
        </header>

        <section class="content">

            @if(session('success'))
                <div style="background-color: #eff6ff; color: #1d4ed8; padding: 14px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; border: 1px solid #bfdbfe;">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div style="background-color: #fee2e2; color: #dc2626; padding: 14px 20px; border-radius: 8px; margin-bottom: 20px; font-weight: 700; border: 1px solid #fecaca;">
                    <ul style="list-style: none; margin: 0; padding: 0;">
                        @foreach($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-xmark"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="page-head">
                <div>
                    <h1>Postulantes</h1>
                    <p>Administra y consulta la información de los postulantes.</p>
                </div>

                <button class="btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Agregar Postulante
                </button>
            </div>

            <div class="tools">
                <div class="tool-box">
                    <input type="text" id="buscar" placeholder="Buscar por nombre, CI o correo...">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

                <div class="tool-box">
                    <select id="estadoFiltro">
                        <option value="Todos">Todos los estados</option>
                        <option value="Inscrito">Inscrito</option>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En revisión">En revisión</option>
                    </select>
                    <i class="fa-solid fa-filter"></i>
                </div>
            </div>

            <div class="page-grid">
                <div class="card list-card">
                    <h3 class="card-title">Lista de Postulantes</h3>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Postulante</th>
                                    <th>CI</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaPostulantes"></tbody>
                        </table>
                    </div>

                    <div class="list-footer">
                        <span>Mostrando 1 a 10 de 25 postulantes</span>
                        <div class="pagination">
                            <button class="page-btn"><i class="fa-solid fa-chevron-left"></i></button>
                            <button class="page-btn active">1</button>
                            <button class="page-btn">2</button>
                            <button class="page-btn">3</button>
                            <button class="page-btn"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>

                <div class="card detail-card">
                    <div class="detail-header">
                        <h3>Detalle del Postulante</h3>
                        <button class="close-btn">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="student-profile">
                        <div class="student-avatar">
                            <i class="fa-solid fa-user"></i>
                        </div>

                        <div>
                            <div class="student-name" id="detalleNombre">Juan Pérez López</div>
                            <div class="student-info">CI: <span id="detalleCi">72912345</span></div>
                            <div class="student-info" id="detalleCorreo">Correo: juan.perez@gmail.com</div>
                            <div class="student-info">
                                Estado:
                                <span class="badge badge-inscrito" id="detalleEstado">Inscrito</span>
                            </div>
                        </div>
                    </div>

                    <div class="tabs">
                        <button class="tab active">Elección de Carrera</button>
                        <button class="tab">Inscripción</button>
                        <button class="tab">Documentos</button>
                        <button class="tab">Historial</button>
                    </div>

                    <div class="detail-body">
                        <div class="detail-section-head">
                            <div class="detail-icon">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                            <div>
                                <h4>Elección de Carrera</h4>
                                <p>Selecciona la carrera principal y, de ser el caso, una carrera secundaria.</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Carrera Principal <span class="required">*</span></label>
                            <select>
                                <option>Selecciona la carrera principal</option>
                                <option>Ingeniería en Sistemas</option>
                                <option>Ingeniería en Redes y Telecomunicaciones</option>
                                <option>Ingeniería Informática</option>
                                <option>Ciencias de la Computación</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Carrera Secundaria (Opcional)</label>
                            <select>
                                <option>Selecciona la carrera secundaria</option>
                                <option>Ingeniería en Sistemas</option>
                                <option>Ingeniería en Redes y Telecomunicaciones</option>
                                <option>Ingeniería Informática</option>
                                <option>Ciencias de la Computación</option>
                            </select>
                        </div>

                        <div class="info-box">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>La carrera secundaria es opcional y se considerará solo si no hay vacantes en la principal.</span>
                        </div>

                        <form id="formInscribir"
                              action="{{ route('inscripcion.store') }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="Id_postulante" id="inputIdPostulante" value="">
                            <input type="hidden" name="datos_basicos" value="[]">

                            <button type="submit" class="inscribir-btn">
                                <i class="fa-regular fa-clipboard"></i>
                                Inscribir Postulante
                            </button>
                        </form>

                        <div class="note">
                            Al inscribir, se creará su registro de inscripción y podrá continuar con el proceso.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<script>
    // Datos desde el backend (BD real)
    const postulantes = @json($postulantes);

    const tabla = document.getElementById('tablaPostulantes');
    const buscar = document.getElementById('buscar');
    const estadoFiltro = document.getElementById('estadoFiltro');

    function claseEstado(estado) {
        if (estado === 'Inscrito') return 'badge-inscrito';
        if (estado === 'Pendiente') return 'badge-pendiente';
        return 'badge-revision';
    }

    function renderTabla(lista = postulantes) {
        tabla.innerHTML = '';

        lista.forEach((p, index) => {
            tabla.innerHTML += `
                <tr onclick="seleccionarPostulante('${p.id}')" class="${index === 0 ? 'selected' : ''}">
                    <td>${p.id}</td>
                    <td>${p.nombre}</td>
                    <td>${p.ci}</td>
                    <td>${p.correo}</td>
                    <td><span class="badge ${claseEstado(p.estado)}">${p.estado}</span></td>
                </tr>
            `;
        });
    }

    function seleccionarPostulante(id) {
        const p = postulantes.find(item => item.id === id);
        if (!p) return;

        document.getElementById('detalleNombre').textContent = p.nombre;
        document.getElementById('detalleCi').textContent = p.ci;
        document.getElementById('detalleCorreo').textContent = 'Correo: ' + p.correo;

        const estado = document.getElementById('detalleEstado');
        estado.textContent = p.estado;
        estado.className = 'badge ' + claseEstado(p.estado);

        // Actualiza el campo oculto con el ID numérico del postulante
        const idNumerico = parseInt(p.id.replace('PST-', ''), 10);
        document.getElementById('inputIdPostulante').value = idNumerico;

        document.querySelectorAll('tbody tr').forEach(row => row.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
    }

    function filtrar() {
        const texto = buscar.value.toLowerCase();
        const estado = estadoFiltro.value;

        const filtrados = postulantes.filter(p => {
            const coincideTexto =
                p.nombre.toLowerCase().includes(texto) ||
                p.ci.toLowerCase().includes(texto) ||
                p.correo.toLowerCase().includes(texto);

            const coincideEstado = estado === 'Todos' || p.estado === estado;

            return coincideTexto && coincideEstado;
        });

        renderTabla(filtrados);
    }

    buscar.addEventListener('input', filtrar);
    estadoFiltro.addEventListener('change', filtrar);

    document.getElementById('menuToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('open');
    });

    renderTabla();
</script>

</body>
</html>