<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestión de Usuarios - CUP UAGRM</title>

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
        --text-primary: #1e293b;
        --text-secondary: #64748b;
        --text-muted: #94a3b8;
        --accent-blue: #1e40af;
        --accent-red: #e31c3d;
        --card-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
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
    }

    .dashboard-container {
        display: flex;
        width: 100%;
        min-height: 100vh;
    }

    .sidebar {
        width: 280px;
        background-color: var(--sidebar-bg);
        color: #fff;
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 100;
    }

    .sidebar-header {
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
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
        color: #fff;
        box-shadow: 0 4px 10px rgba(227,28,61,0.3);
    }

    .logo-title {
        font-size: 24px;
        font-weight: 900;
        color: #fff;
        line-height: 1;
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
    }

    .sidebar-menu {
        flex: 1;
        overflow-y: auto;
        padding: 20px 14px;
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
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        padding: 12px 16px;
        font-size: 13.5px;
        font-weight: 500;
        border-radius: 12px;
        margin-bottom: 4px;
        transition: all 0.25s ease;
    }

    .sidebar-menu a i {
        font-size: 16px;
        width: 20px;
        text-align: center;
        color: rgba(255,255,255,0.5);
    }

    .sidebar-menu a:hover {
        background-color: var(--sidebar-hover);
        color: #fff;
    }

    .sidebar-menu a.active {
        background-color: var(--sidebar-active);
        color: #fff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(227,28,61,0.2);
    }

    .sidebar-menu a.active i {
        color: #fff;
    }

    .main-content {
        flex: 1;
        margin-left: 280px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .topbar {
        background-color: #fff;
        height: 70px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        position: sticky;
        top: 0;
        z-index: 50;
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
    }

    .notification-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background-color: var(--sidebar-active);
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }

    .profile-pill {
        display: flex;
        align-items: center;
        gap: 12px;
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
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px var(--accent-blue);
    }

    .profile-info {
        display: flex;
        flex-direction: column;
    }

    .profile-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-primary);
    }

    /*.profile-role {
        font-size: 10px;
        font-weight: 600;
        color: var(--text-secondary);
    }*/

    .content-body {
        padding: 32px;
        max-width: 1400px;
        width: 100%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 26px;
    }

    .page-header h2 {
        font-size: 28px;
        color: #0b1d4a;
        font-weight: 800;
    }

    .page-header p {
        color: var(--text-secondary);
        margin-top: 5px;
        font-size: 14px;
    }

    .card {
        background: #fff;
        border: var(--card-border);
        border-radius: 22px;
        box-shadow: var(--card-shadow);
        padding: 28px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--accent-blue);
        font-size: 17px;
        font-weight: 800;
        margin-bottom: 20px;
        padding-bottom: 18px;
        border-bottom: 1px solid #e2e8f0;
    }

    .section-title i {
        font-size: 20px;
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

    .form-group label {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
    }

    .required {
        color: var(--accent-red);
    }

    .form-group input,
    .form-group select {
        width: 100%;
        height: 48px;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        padding: 0 14px;
        font-family: 'Outfit', sans-serif;
        font-size: 13.5px;
        color: var(--text-primary);
        outline: none;
        background-color: #fff;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(30,64,175,0.08);
    }

    .password-wrapper {
        position: relative;
    }

    .password-wrapper input {
        padding-right: 44px;
    }

    .password-wrapper i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        cursor: pointer;
    }

    .role-box {
        margin-top: 28px;
        border-top: 1px solid #e2e8f0;
        padding-top: 24px;
    }

    .info-box {
        margin-top: 18px;
        border: 1px solid #bfdbfe;
        background-color: #eff6ff;
        color: #1e40af;
        border-radius: 10px;
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 13.5px;
        font-weight: 600;
    }

    .form-actions {
        margin-top: 26px;
        display: flex;
        justify-content: flex-end;
        gap: 14px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }

    .btn {
        height: 46px;
        padding: 0 24px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        font-family: 'Outfit', sans-serif;
        font-size: 13px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #0052cc;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #003f9e;
    }

    .btn-secondary {
        background-color: #fff;
        color: #0f172a;
        border: 1px solid #dbe2ea;
    }

    .btn-secondary:hover {
        background-color: #f8fafc;
    }

    .btn-danger {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .btn-danger:hover {
        background-color: #fecaca;
    }

    .btn-small {
        height: 34px;
        padding: 0 12px;
        border-radius: 8px;
        font-size: 12px;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        gap: 16px;
    }

    .table-header h3 {
        color: #0b1d4a;
        font-size: 18px;
        font-weight: 800;
    }

    .search-input {
        width: 280px;
        height: 42px;
        border: 1px solid #dbe2ea;
        border-radius: 10px;
        padding: 0 14px;
        outline: none;
        font-family: 'Outfit', sans-serif;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        overflow: hidden;
    }

    .users-table th {
        text-align: left;
        font-size: 12px;
        color: #64748b;
        font-weight: 800;
        text-transform: uppercase;
        padding: 14px 12px;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .users-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13.5px;
        color: #334155;
        vertical-align: middle;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: rgba(30,64,175,0.1);
        color: var(--accent-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
    }

    .badge {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
    }

    .badge-active {
        background-color: #dcfce7;
        color: #15803d;
    }

    .badge-inactive {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .role-badge {
        background-color: #eff6ff;
        color: #1e40af;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 24px;
        color: #64748b;
        font-weight: 600;
    }

    .dashboard-footer {
        padding: 24px 32px;
        border-top: 1px solid #e2e8f0;
        text-align: center;
        font-size: 12px;
        font-weight: 500;
        color: var(--text-secondary);
        background-color: #fff;
        margin-top: auto;
    }

    @media (max-width: 1024px) {
        .form-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            display: none;
        }

        .main-content {
            margin-left: 0;
        }

        .content-body {
            padding: 20px 16px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .table-header {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input {
            width: 100%;
        }

        .users-table {
            min-width: 900px;
        }

        .table-responsive {
            overflow-x: auto;
        }
    }
</style>
</head>

<body>
<div class="dashboard-container">

    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <div class="logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                    <div class="logo-title">CUP<span>.</span></div>
                    <div class="logo-subtitle">Preuniversitario</div>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <div class="sidebar-section-title">Gestión Académica</div>
            <a href="{{ route('admin.carreras') }}"><i class="fa-solid fa-university"></i><span>Carreras y Cupos</span></a>
            <a href="#"><i class="fa-solid fa-users-rectangle"></i><span>Grupos</span></a>
            <a href="#"><i class="fa-solid fa-door-open"></i><span>Aulas</span></a>
            <a href="#"><i class="fa-solid fa-calendar-alt"></i><span>Horario</span></a>
            <a href="#"><i class="fa-solid fa-book-open"></i><span>Materias</span></a>
            <a href="#"><i class="fa-solid fa-star"></i><span>Preferencias CUP</span></a>

            <div class="sidebar-section-title">Inscripción y Documentación</div>
            <a href="#"><i class="fa-solid fa-user-graduate"></i><span>Postulantes</span></a>
            <a href="#"><i class="fa-solid fa-file-signature"></i><span>Inscripciones</span></a>
            <a href="#"><i class="fa-solid fa-folder-open"></i><span>Documentos</span></a>

            <div class="sidebar-section-title">Usuario, Seguridad y Auditoría</div>
            <a href="{{ route('usuarios.roles') }}" class="active">
                <i class="fa-solid fa-user-shield"></i>
                <span>Usuarios</span>
            </a>
            <a href="#"><i class="fa-solid fa-tags"></i><span>Roles</span></a>
            <a href="#"><i class="fa-solid fa-history"></i><span>Bitácora</span></a>

            <div class="sidebar-section-title">Logística y Reportes</div>
            <a href="#"><i class="fa-solid fa-chart-line"></i><span>Reportes</span></a>
            <a href="#"><i class="fa-solid fa-file-export"></i><span>Exportaciones</span></a>

            <div style="border-top: 1px solid rgba(255,255,255,0.05); margin: 20px 0 10px 0;"></div>
            <a href="#"><i class="fa-solid fa-cog"></i><span>Configuración</span></a>
            <a href="#" style="color:#f87171;">
                <i class="fa-solid fa-sign-out-alt" style="color:#f87171;"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <a href="{{ route('dashboard') }}" class="back-dashboard-btn" title="Volver al Dashboard">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <button class="menu-toggle"><i class="fa-solid fa-bars"></i></button>
                <span class="page-title">Gestión de Usuarios</span>
            </div>

            <div class="topbar-right">
                <button class="notification-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notification-badge">3</span>
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

        <div class="content-body">
            <div class="page-header">
                <h2 id="formTitle">Nuevo Usuario</h2>
                <p>Complete la información del usuario y asigne un rol.</p>
            </div>

            <div class="card">
                <div class="section-title">
                    <i class="fa-regular fa-id-badge"></i>
                    <span>Datos del Usuario</span>
                </div>

                <form id="userForm" action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="userId">

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nombre Completo <span class="required">*</span></label>
                            <input type="text" id="nombreCompleto" name="nombre_completo" placeholder="Ingrese el nombre completo" required>
                        </div>

                        <div class="form-group">
                            <label>Nombre de Usuario <span class="required">*</span></label>
                            <input type="text" id="nombreUsuario" name="nombre_usuario" placeholder="Ingrese el nombre de usuario" required>
                        </div>

                        <div class="form-group">
                            <label>Correo Electrónico <span class="required">*</span></label>
                            <input type="email" id="correo" name="correo" placeholder="Ingrese el correo electrónico" required>
                        </div>

                        <div class="form-group">
                            <label>Contraseña <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="contrasena" name="password" placeholder="Ingrese la contraseña" required>
                                <i class="fa-regular fa-eye" onclick="togglePassword('contrasena', this)"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Confirmar Contraseña <span class="required">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="confirmarContrasena" name="password_confirmation" placeholder="Confirme la contraseña" required>
                                <i class="fa-regular fa-eye" onclick="togglePassword('confirmarContrasena', this)"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>DNI <span class="required">*</span></label>
                            <input type="text" id="dni" name="dni" placeholder="Ingrese el DNI" required>
                        </div>

                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" id="telefono" name="telefono" placeholder="Ingrese el número de teléfono">
                        </div>

                        <div class="form-group">
                            <label>Estado <span class="required">*</span></label>
                            <select id="estado" name="estado" required>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="role-box">
                        <div class="section-title">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>Rol y Permisos</span>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Rol <span class="required">*</span></label>
                                <select id="rol" name="id_rol" required>
                                    <option value="">Seleccione un rol</option>
                                    @foreach($roles as $rol)
                                        <option value="{{ $rol['id'] }}">{{ $rol['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="info-box">
                            <i class="fa-solid fa-circle-info"></i>
                            <span id="roleInfo">Los permisos se asignarán automáticamente según el rol seleccionado.</span>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="limpiarFormulario()">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="fa-solid fa-user-plus"></i>
                            Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="table-header">
                    <h3>Usuarios Registrados</h3>
                    <input type="text" class="search-input" id="buscarUsuario" placeholder="Buscar usuario...">
                </div>

                <div class="table-responsive">
                    <table class="users-table">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Correo</th>
                                <th>CI</th>
                                <th>Teléfono</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="usuariosTabla"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="dashboard-footer">
            © 2026 CUP Preuniversitario. Todos los derechos reservados.
        </div>
    </div>
</div>
<script>
    // Datos reales cargados desde el backend
    const roles = @json($roles);
    let usuarios = @json($usuarios);

    // Renderizar la tabla con usuarios
    function renderTable(usersList = usuarios) {
        const tbody = document.getElementById('usuariosTabla');
        tbody.innerHTML = '';
        if (usersList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No se encontraron usuarios</td></tr>';
            return;
        }
        usersList.forEach(u => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div class="user-cell">
                        <div class="user-avatar">${u.nombre_completo.substring(0, 2).toUpperCase()}</div>
                        <div style="font-weight:700;">${u.usuario}</div>
                    </div>
                </td>
                <td>${u.correo}</td>
                <td>${u.dni}</td>
                <td>${u.telefono || 'N/A'}</td>
                <td><span class="badge role-badge">${u.rol}</span></td>
                <td><span class="badge ${u.estado === 'Activo' ? 'badge-active' : 'badge-inactive'}">${u.estado}</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-secondary btn-small" onclick="editarUsuario(${u.id})" type="button"><i class="fa-solid fa-pen-to-square"></i></button>
                        
                        <form action="/usuarios-roles/${u.id}" method="POST" style="display:inline;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                            @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-small"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    // Toggle de visibilidad de contraseña
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Búsqueda en tiempo real
    document.getElementById('buscarUsuario').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const filtered = usuarios.filter(u => 
            u.nombre_completo.toLowerCase().includes(term) || 
            u.usuario.toLowerCase().includes(term) || 
            u.correo.toLowerCase().includes(term) || 
            u.dni.toLowerCase().includes(term)
        );
        renderTable(filtered);
    });

    // Cargar formulario para edición
    function editarUsuario(id) {
        const u = usuarios.find(x => x.id == id);
        if (!u) return;
        
        document.getElementById('formTitle').innerText = 'Editar Usuario';
        document.getElementById('userForm').action = '/usuarios-roles/' + u.id;
        
        let methodInput = document.getElementById('userFormMethod');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.id = 'userFormMethod';
            document.getElementById('userForm').appendChild(methodInput);
        }
        methodInput.value = 'PUT';
        
        document.getElementById('userId').value = u.id;
        document.getElementById('nombreCompleto').value = u.nombre_completo;
        document.getElementById('nombreUsuario').value = u.usuario;
        document.getElementById('correo').value = u.correo;
        document.getElementById('dni').value = u.dni;
        document.getElementById('telefono').value = u.telefono === 'N/A' ? '' : u.telefono;
        document.getElementById('estado').value = u.estado;
        document.getElementById('rol').value = u.id_rol;
        
        document.getElementById('contrasena').required = false;
        document.getElementById('confirmarContrasena').required = false;
        document.getElementById('btnGuardar').innerHTML = '<i class="fa-solid fa-user-pen"></i> Actualizar Usuario';
    }

    // Resetear formulario
    function limpiarFormulario() {
        document.getElementById('formTitle').innerText = 'Nuevo Usuario';
        document.getElementById('userForm').action = '{{ route("usuarios.store") }}';
        
        const methodInput = document.getElementById('userFormMethod');
        if (methodInput) {
            methodInput.remove();
        }
        
        document.getElementById('userId').value = '';
        document.getElementById('nombreCompleto').value = '';
        document.getElementById('nombreUsuario').value = '';
        document.getElementById('correo').value = '';
        document.getElementById('contrasena').value = '';
        document.getElementById('confirmarContrasena').value = '';
        document.getElementById('dni').value = '';
        document.getElementById('telefono').value = '';
        document.getElementById('estado').value = 'Activo';
        document.getElementById('rol').value = '';
        
        document.getElementById('contrasena').required = true;
        document.getElementById('confirmarContrasena').required = true;
        document.getElementById('btnGuardar').innerHTML = '<i class="fa-solid fa-user-plus"></i> Guardar Usuario';
    }

    // Inicializar la tabla
    document.addEventListener('DOMContentLoaded', () => {
        renderTable();
    });
</script>

</body>
</html>