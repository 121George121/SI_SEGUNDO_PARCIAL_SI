@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    @php
        // Filter users by role
        $administradores = $usuarios->filter(function($u) {
            return $u->isSuperAdmin() || ($u->rol && $u->rol->nombre_rol === 'Admin');
        });
        
        $docentes = $usuarios->filter(function($u) {
            return $u->isDocente();
        });
        
        $postulantes = $usuarios->filter(function($u) {
            return $u->isPostulante();
        });
    @endphp

    <!-- Title Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight" id="page-title">Gestión de Usuarios y Personas</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1" id="page-subtitle">Administra los administradores del sistema.</p>
        </div>
        @if(!Auth::user()->isDocente())
        <button id="btn-add-user" onclick="openCreateModal()" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-circle-plus text-sm"></i>
            <span id="btn-add-user-text">Añadir Administrador</span>
        </button>
        @endif
    </div>

    <!-- Reactive Tabs Row -->
    <div class="flex items-center space-x-12 bg-white px-8 py-4 rounded-3xl border border-slate-100 shadow-sm mb-6">
        <!-- Administradores Tab -->
        <button onclick="switchTab('administradores')" id="tab-btn-administradores" class="flex items-center space-x-3 pb-3 border-b-2 border-blue-600 text-blue-600 font-extrabold text-sm transition-all focus:outline-none">
            <i class="fa-solid fa-users text-blue-500"></i>
            <span>Administradores</span>
            <span class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">{{ $administradores->count() }}</span>
        </button>
        <!-- Docentes Tab -->
        <button onclick="switchTab('docentes')" id="tab-btn-docentes" class="flex items-center space-x-3 pb-3 border-b-2 border-transparent text-slate-400 font-extrabold text-sm hover:text-slate-650 transition-all focus:outline-none">
            <i class="fa-solid fa-chalkboard-user text-emerald-500"></i>
            <span>Docentes</span>
            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold">{{ $docentes->count() }}</span>
        </button>
        <!-- Postulantes Tab -->
        <button onclick="switchTab('postulantes')" id="tab-btn-postulantes" class="flex items-center space-x-3 pb-3 border-b-2 border-transparent text-slate-400 font-extrabold text-sm hover:text-slate-650 transition-all focus:outline-none">
            <i class="fa-solid fa-user-graduate text-purple-500"></i>
            <span>Postulantes</span>
            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold">{{ $postulantes->count() }}</span>
        </button>
    </div>

    <!-- Card Container for Search and Tables -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-6">
        
        <!-- Search bar and filter button -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="search-input" onkeyup="filterTable()" placeholder="Buscar por nombre, correo o CI..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-700 placeholder-slate-400 transition-all">
            </div>
            <button class="flex items-center space-x-2 px-4 py-2.5 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm">
                <i class="fa-solid fa-sliders text-xs text-slate-500"></i>
                <span>Filtrar</span>
            </button>
        </div>

        <!-- ------------------------------------------------------------- -->
        <!-- TABLE 1: ADMINISTRADORES                                      -->
        <!-- ------------------------------------------------------------- -->
        <div id="content-administradores" class="tab-content overflow-hidden">
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse" id="table-administradores">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="py-4 px-6">Nombre Completo</th>
                            <th class="py-4 px-6">CI</th>
                            <th class="py-4 px-6">Username</th>
                            <th class="py-4 px-6">Correo</th>
                            <th class="py-4 px-6">Rol</th>
                            <th class="py-4 px-6">Estado</th>
                            @if(!Auth::user()->isDocente())
                            <th class="py-4 px-6 text-center">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($administradores as $user)
                            @php
                                $words = explode(' ', $user->persona->nombre_completo);
                                $initials = '';
                                if (count($words) >= 2) {
                                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                } else if (count($words) == 1) {
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                } else {
                                    $initials = 'AD';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-[11px] mr-3 flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <span class="font-extrabold text-slate-800">{{ $user->persona->nombre_completo }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-650">{{ $user->persona->ci }}</td>
                                <td class="py-4 px-6 text-slate-500 font-medium">{{ $user->nombre_usuario }}</td>
                                <td class="py-4 px-6 text-slate-400 font-medium">{{ $user->correo }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ $user->rol->nombre_rol === 'SuperAdministrador' ? 'Super Administrador' : 'Administrador' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                        {{ $user->estado }}
                                    </span>
                                </td>
                                @if(!Auth::user()->isDocente())
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if(Auth::user()->isSuperAdmin())
                                        <button data-user="{{ json_encode($user) }}" onclick="openEditModal(this)" class="w-8 h-8 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center transition-colors" title="Editar">
                                            <i class="fa-solid fa-pencil text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.usuarios.destroy', $user->id_usuario) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este usuario y su persona asociada?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center transition-colors" title="Eliminar">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                        @else
                                        <span class="text-slate-400 text-[10px] font-bold">Sin permisos</span>
                                        @endif
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-450 font-bold">No hay administradores registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-slate-100 mt-4 text-xs font-semibold text-slate-450">
                <span id="count-text-administradores">Mostrando 1 a {{ $administradores->count() }} de {{ $administradores->count() }} administradores</span>
                <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>
                    <span class="w-8 h-8 bg-blue-50 border border-blue-200 text-blue-600 font-bold rounded-lg flex items-center justify-center text-xs">1</span>
                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------------------------- -->
        <!-- TABLE 2: DOCENTES                                             -->
        <!-- ------------------------------------------------------------- -->
        <div id="content-docentes" class="tab-content overflow-hidden hidden">
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse" id="table-docentes">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="py-4 px-6">Nombre Completo</th>
                            <th class="py-4 px-6">CI</th>
                            <th class="py-4 px-6">Username</th>
                            <th class="py-4 px-6">Correo</th>
                            <th class="py-4 px-6">Rol</th>
                            <th class="py-4 px-6">Estado</th>
                            @if(!Auth::user()->isDocente())
                            <th class="py-4 px-6 text-center">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($docentes as $user)
                            @php
                                $words = explode(' ', $user->persona->nombre_completo);
                                $initials = '';
                                if (count($words) >= 2) {
                                    $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                                } else if (count($words) == 1) {
                                    $initials = strtoupper(substr($words[0], 0, 2));
                                } else {
                                    $initials = 'DO';
                                }
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-[11px] mr-3 flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <span class="font-extrabold text-slate-800">{{ $user->persona->nombre_completo }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-650">{{ $user->persona->ci }}</td>
                                <td class="py-4 px-6 text-slate-500 font-medium">{{ $user->nombre_usuario }}</td>
                                <td class="py-4 px-6 text-slate-400 font-medium">{{ $user->correo }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        {{ $user->rol->nombre_rol }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                        {{ $user->estado }}
                                    </span>
                                </td>
                                @if(!Auth::user()->isDocente())
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button data-user="{{ json_encode($user) }}" onclick="openEditModal(this)" class="w-8 h-8 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center transition-colors" title="Editar">
                                            <i class="fa-solid fa-pencil text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.usuarios.destroy', $user->id_usuario) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este usuario y su persona asociada?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center transition-colors" title="Eliminar">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-450 font-bold">No hay docentes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-slate-100 mt-4 text-xs font-semibold text-slate-450">
                <span id="count-text-docentes">Mostrando 1 a {{ $docentes->count() }} de {{ $docentes->count() }} docentes</span>
                <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>
                    <span class="w-8 h-8 bg-emerald-50 border border-emerald-200 text-emerald-600 font-bold rounded-lg flex items-center justify-center text-xs">1</span>
                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- ------------------------------------------------------------- -->
        <!-- TABLE 3: POSTULANTES                                          -->
        <!-- ------------------------------------------------------------- -->
        <div id="content-postulantes" class="tab-content overflow-hidden hidden">
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse" id="table-postulantes">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="py-4 px-6">Nombre Completo</th>
                            <th class="py-4 px-6">CI</th>
                            <th class="py-4 px-6">Username</th>
                            <th class="py-4 px-6">Correo</th>
                            <th class="py-4 px-6">Rol</th>
                            <th class="py-4 px-6">Estado</th>
                            @if(!Auth::user()->isDocente())
                            <th class="py-4 px-6 text-center">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($postulantes as $user)
                            @php
                                $words = explode(' ', $user->persona->nombre_completo);
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
                                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-[11px] mr-3 flex-shrink-0">
                                            {{ $initials }}
                                        </div>
                                        <span class="font-extrabold text-slate-800">{{ $user->persona->nombre_completo }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 font-medium text-slate-650">{{ $user->persona->ci }}</td>
                                <td class="py-4 px-6 text-slate-500 font-medium">{{ $user->nombre_usuario }}</td>
                                <td class="py-4 px-6 text-slate-400 font-medium">{{ $user->correo }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-purple-50 text-purple-600 border border-purple-100">
                                        {{ $user->rol->nombre_rol }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-emerald-50 text-emerald-600">
                                        {{ $user->estado }}
                                    </span>
                                </td>
                                @if(!Auth::user()->isDocente())
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button data-user="{{ json_encode($user) }}" onclick="openEditModal(this)" class="w-8 h-8 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center transition-colors" title="Editar">
                                            <i class="fa-solid fa-pencil text-xs"></i>
                                        </button>
                                        <form action="{{ route('admin.usuarios.destroy', $user->id_usuario) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar este usuario y su persona asociada?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg flex items-center justify-center transition-colors" title="Eliminar">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-450 font-bold">No hay postulantes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-slate-100 mt-4 text-xs font-semibold text-slate-450">
                <span id="count-text-postulantes">Mostrando 1 a {{ $postulantes->count() }} de {{ $postulantes->count() }} postulantes</span>
                <div class="flex items-center space-x-2 mt-4 sm:mt-0">
                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </button>
                    <span class="w-8 h-8 bg-purple-50 border border-purple-200 text-purple-600 font-bold rounded-lg flex items-center justify-center text-xs">1</span>
                    <button class="w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center hover:bg-slate-50 text-slate-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Form (Create Usuario) -->
    <div id="modal-usuario" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] border border-slate-100 animate-fade-in">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-user-plus text-blue-600 mr-2 text-base"></i>
                    <span>Registrar Nuevo Usuario</span>
                </h3>
                <button onclick="document.getElementById('modal-usuario').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form action="{{ route('admin.usuarios.store') }}" method="POST" class="p-6 overflow-y-auto space-y-5 flex-1 text-xs">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- CI -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Cédula de Identidad (CI) *</label>
                        <input type="text" name="ci" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. 9694251">
                    </div>

                    <!-- Rol -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Rol del Sistema *</label>
                        <select name="id_rol" id="modal-id-rol" required onchange="handleRoleChange()" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Rol</option>
                            @foreach($roles as $r)
                                @if(Auth::user()->isSuperAdmin() || ($r->nombre_rol !== 'SuperAdministrador' && $r->nombre_rol !== 'Admin'))
                                    <option value="{{ $r->id_rol }}">{{ $r->nombre_rol }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                        <input type="text" name="nombre" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Jorge">
                    </div>

                    <!-- Apellido -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                        <input type="text" name="apellido" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Alanoca">
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Telefono -->
                    <div class="space-y-1.5" id="div-telefono">
                        <label class="text-xs font-bold text-slate-700">Teléfono</label>
                        <input type="text" name="telefono" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. 78945612">
                    </div>

                    <!-- Correo -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Correo Electrónico *</label>
                        <input type="email" name="correo" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. jorge@uagrm.edu.bo">
                    </div>

                    <!-- Direccion -->
                    <div class="space-y-1.5" id="div-direccion">
                        <label class="text-xs font-bold text-slate-700">Dirección</label>
                        <input type="text" name="direccion" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Av. Bush 2do Anillo">
                    </div>

                    <!-- Username -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Username *</label>
                        <input type="text" name="nombre_usuario" required autocomplete="new-password" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. jorgea">
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Contraseña *</label>
                        <input type="password" name="contraseña" required autocomplete="new-password" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Mínimo 8 caracteres">
                    </div>

                    <!-- Cargo (Admin only details) -->
                    <div class="space-y-1.5" id="div-cargo">
                        <label class="text-xs font-bold text-slate-700">Cargo (Solo si es Administrador)</label>
                        <input type="text" name="cargo" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Encargado de Registro">
                    </div>

                    <!-- Area (Admin only details) -->
                    <div class="space-y-1.5" id="div-area">
                        <label class="text-xs font-bold text-slate-700">Área (Solo si es Administrador)</label>
                        <input type="text" name="area" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Admisión">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-usuario').classList.add('hidden')" class="px-5 py-3 bg-slate-150 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-md transition-colors">Guardar Usuario</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal Form (Edit Usuario) -->
    <div id="modal-editar-usuario" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh] border border-slate-100 animate-fade-in">
            
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-user-pen text-blue-600 mr-2 text-base"></i>
                    <span>Editar Información del Usuario</span>
                </h3>
                <button onclick="document.getElementById('modal-editar-usuario').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="form-editar-usuario" method="POST" onsubmit="return confirm('¿Está seguro de guardar los cambios para este usuario?')" class="p-6 overflow-y-auto space-y-5 flex-1 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- CI -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Cédula de Identidad (CI) *</label>
                        <input type="text" name="ci" id="edit-ci" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. 9694251">
                    </div>

                    <!-- Rol -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Rol del Sistema *</label>
                        <select name="id_rol" id="edit-modal-id-rol" required onchange="handleEditRoleChange()" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800 bg-white">
                            <option value="">Seleccionar Rol</option>
                            @foreach($roles as $r)
                                @if(Auth::user()->isSuperAdmin() || ($r->nombre_rol !== 'SuperAdministrador' && $r->nombre_rol !== 'Admin'))
                                    <option value="{{ $r->id_rol }}">{{ $r->nombre_rol }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                        <input type="text" name="nombre" id="edit-nombre" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Jorge">
                    </div>

                    <!-- Apellido -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                        <input type="text" name="apellido" id="edit-apellido" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Alanoca">
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                        <input type="date" name="fecha_nacimiento" id="edit-fecha_nacimiento" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800">
                    </div>

                    <!-- Telefono -->
                    <div class="space-y-1.5" id="edit-div-telefono">
                        <label class="text-xs font-bold text-slate-700">Teléfono</label>
                        <input type="text" name="telefono" id="edit-telefono" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. 78945612">
                    </div>

                    <!-- Correo -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Correo Electrónico *</label>
                        <input type="email" name="correo" id="edit-correo" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. jorge@uagrm.edu.bo">
                    </div>

                    <!-- Direccion -->
                    <div class="space-y-1.5" id="edit-div-direccion">
                        <label class="text-xs font-bold text-slate-700">Dirección</label>
                        <input type="text" name="direccion" id="edit-direccion" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Av. Bush 2do Anillo">
                    </div>

                    <!-- Username -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Username *</label>
                        <input type="text" name="nombre_usuario" id="edit-nombre_usuario" required autocomplete="new-password" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. jorgea">
                    </div>

                    <!-- Password -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Contraseña (Dejar en blanco para mantener la actual)</label>
                        <input type="password" name="contraseña" id="edit-contraseña" autocomplete="new-password" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Contraseña nueva">
                    </div>

                    <!-- Cargo (Admin/SuperAdmin only details) -->
                    <div class="space-y-1.5" id="edit-div-cargo">
                        <label class="text-xs font-bold text-slate-700">Cargo (Solo si es Administrador/Superadmin)</label>
                        <input type="text" name="cargo" id="edit-cargo" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Encargado de Registro">
                    </div>

                    <!-- Area (Admin only details) -->
                    <div class="space-y-1.5" id="edit-div-area">
                        <label class="text-xs font-bold text-slate-700">Área (Solo si es Administrador)</label>
                        <input type="text" name="area" id="edit-area" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 text-xs font-semibold text-slate-800" placeholder="Ej. Admisión">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <button type="button" onclick="document.getElementById('modal-editar-usuario').classList.add('hidden')" class="px-5 py-3 bg-slate-150 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-xs transition-colors">Cancelar</button>
                    <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-xs shadow-md transition-colors">Guardar Cambios</button>
                </div>
            </form>

        </div>
    </div>

</div>

<script>
    // Dynamic Role Form Fields Manager
    function handleRoleChange() {
        const select = document.getElementById('modal-id-rol');
        if (!select) return;
        const selectedOption = select.options[select.selectedIndex];
        const roleName = selectedOption ? selectedOption.text.trim().toLowerCase() : '';

        const divTelefono = document.getElementById('div-telefono');
        const divDireccion = document.getElementById('div-direccion');
        const divCargo = document.getElementById('div-cargo');
        const divArea = document.getElementById('div-area');

        if (!divTelefono || !divDireccion || !divCargo || !divArea) return;

        const inputTelefono = divTelefono.querySelector('input');
        const inputDireccion = divDireccion.querySelector('input');
        const inputCargo = divCargo.querySelector('input');
        const inputArea = divArea.querySelector('input');

        if (roleName === 'superadministrador' || roleName === 'admin') {
            // Show all fields
            divTelefono.classList.remove('hidden');
            divDireccion.classList.remove('hidden');
            divCargo.classList.remove('hidden');
            divArea.classList.remove('hidden');

            inputTelefono.disabled = false;
            inputDireccion.disabled = false;
            inputCargo.disabled = false;
            inputArea.disabled = false;
        } else if (roleName === 'docente') {
            // Show: CI, Nombre, Apellido, Fecha de Nacimiento, Teléfono, Correo Electrónico, Username, Contraseña
            divTelefono.classList.remove('hidden');
            divDireccion.classList.add('hidden');
            divCargo.classList.add('hidden');
            divArea.classList.add('hidden');

            inputTelefono.disabled = false;
            inputDireccion.disabled = true;
            inputCargo.disabled = true;
            inputArea.disabled = true;
        } else if (roleName === 'postulante') {
            // Show only essential: CI, Nombre, Apellido, Fecha de Nacimiento, Correo, Username, Contraseña
            divTelefono.classList.add('hidden');
            divDireccion.classList.add('hidden');
            divCargo.classList.add('hidden');
            divArea.classList.add('hidden');

            inputTelefono.disabled = true;
            inputDireccion.disabled = true;
            inputCargo.disabled = true;
            inputArea.disabled = true;
        } else {
            // Default/Fallback: show everything
            divTelefono.classList.remove('hidden');
            divDireccion.classList.remove('hidden');
            divCargo.classList.remove('hidden');
            divArea.classList.remove('hidden');

            inputTelefono.disabled = false;
            inputDireccion.disabled = false;
            inputCargo.disabled = false;
            inputArea.disabled = false;
        }
    }

    // Tab switching mechanism
    function switchTab(tabId) {
        // Hide all tab contents
        document.getElementById('content-administradores').classList.add('hidden');
        document.getElementById('content-docentes').classList.add('hidden');
        document.getElementById('content-postulantes').classList.add('hidden');
        
        // Show active tab content
        document.getElementById('content-' + tabId).classList.remove('hidden');
        
        // Reset tab button states
        ['administradores', 'docentes', 'postulantes'].forEach(id => {
            const btn = document.getElementById('tab-btn-' + id);
            btn.className = "flex items-center space-x-3 pb-3 border-b-2 border-transparent text-slate-400 font-extrabold text-sm hover:text-slate-650 transition-all focus:outline-none";
        });
        
        // Set active tab button state
        const activeBtn = document.getElementById('tab-btn-' + tabId);
        if (tabId === 'administradores') {
            activeBtn.className = "flex items-center space-x-3 pb-3 border-b-2 border-blue-600 text-blue-600 font-extrabold text-sm transition-all focus:outline-none";
        } else if (tabId === 'docentes') {
            activeBtn.className = "flex items-center space-x-3 pb-3 border-b-2 border-emerald-600 text-emerald-600 font-extrabold text-sm transition-all focus:outline-none";
        } else if (tabId === 'postulantes') {
            activeBtn.className = "flex items-center space-x-3 pb-3 border-b-2 border-purple-600 text-purple-600 font-extrabold text-sm transition-all focus:outline-none";
        }
        
        // Update Title/Subtitle and Action Button details dynamically
        const subtitle = document.getElementById('page-subtitle');
        const actionBtn = document.getElementById('btn-add-user');
        const actionBtnText = document.getElementById('btn-add-user-text');
        
        if (actionBtn) {
            // Reset button colors
            actionBtn.className = "px-5 py-3 text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2";
            
            if (tabId === 'administradores') {
                subtitle.innerText = 'Administra los administradores del sistema.';
                actionBtnText.innerText = 'Añadir Administrador';
                actionBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                setModalDefaultRole('Admin');
                @if(!Auth::user()->isSuperAdmin())
                    actionBtn.classList.add('hidden');
                @endif
            } else if (tabId === 'docentes') {
                subtitle.innerText = 'Administra los docentes del sistema.';
                actionBtnText.innerText = 'Añadir Docente';
                actionBtn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                setModalDefaultRole('Docente');
                actionBtn.classList.remove('hidden');
            } else if (tabId === 'postulantes') {
                subtitle.innerText = 'Administra los estudiantes y postulantes registrados.';
                actionBtnText.innerText = 'Añadir Postulante';
                actionBtn.classList.add('bg-purple-600', 'hover:bg-purple-700');
                setModalDefaultRole('Postulante');
                actionBtn.classList.remove('hidden');
            }
        } else {
            // Fallback for role who cannot add users (e.g. Docente)
            if (tabId === 'administradores') {
                subtitle.innerText = 'Administra los administradores del sistema.';
                setModalDefaultRole('Admin');
            } else if (tabId === 'docentes') {
                subtitle.innerText = 'Administra los docentes del sistema.';
                setModalDefaultRole('Docente');
            } else if (tabId === 'postulantes') {
                subtitle.innerText = 'Administra los estudiantes y postulantes registrados.';
                setModalDefaultRole('Postulante');
            }
        }
        
        // Trigger table filtering to apply search query in new tab
        filterTable();
    }

    // Modal role dynamic matching helper
    function setModalDefaultRole(roleName) {
        const select = document.getElementById('modal-id-rol');
        if (!select) return;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].text.toLowerCase() === roleName.toLowerCase()) {
                select.selectedIndex = i;
                break;
            }
        }
        handleRoleChange(); // Update input visibility dynamically based on default role
    }

    // Dynamic search engine
    function filterTable() {
        const query = document.getElementById('search-input').value.toLowerCase();
        // Determine active tab
        let activeTab = 'administradores';
        if (document.getElementById('tab-btn-docentes').classList.contains('text-emerald-600')) {
            activeTab = 'docentes';
        } else if (document.getElementById('tab-btn-postulantes').classList.contains('text-purple-600')) {
            activeTab = 'postulantes';
        }
        
        const table = document.getElementById('table-' + activeTab);
        const tbody = table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        
        let visibleCount = 0;
        
        rows.forEach(row => {
            if (row.classList.contains('no-results-row')) {
                row.remove();
                return;
            }
            
            const name = row.cells[0] ? row.cells[0].textContent.toLowerCase() : '';
            const ci = row.cells[1] ? row.cells[1].textContent.toLowerCase() : '';
            const username = row.cells[2] ? row.cells[2].textContent.toLowerCase() : '';
            const email = row.cells[3] ? row.cells[3].textContent.toLowerCase() : '';
            
            if (name.includes(query) || ci.includes(query) || username.includes(query) || email.includes(query)) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Update footer counts
        const countText = document.getElementById('count-text-' + activeTab);
        const singPlur = activeTab === 'administradores' ? 'administradores' : (activeTab === 'docentes' ? 'docentes' : 'postulantes');
        countText.innerText = `Mostrando ${visibleCount} de ${rows.length} ${singPlur}`;
        
        // Render beautiful no results placeholder
        if (visibleCount === 0 && rows.length > 0) {
            const noResultsTr = document.createElement('tr');
            noResultsTr.className = 'no-results-row';
            noResultsTr.innerHTML = `
                <td colspan="7" class="py-12 text-center text-slate-400 font-bold">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <i class="fa-solid fa-magnifying-glass text-slate-300 text-3xl"></i>
                        <span>No se encontraron resultados para "${query}"</span>
                    </div>
                </td>
            `;
            tbody.appendChild(noResultsTr);
        }
    }

    // Dynamic Edit Role Form Fields Manager
    function handleEditRoleChange() {
        const select = document.getElementById('edit-modal-id-rol');
        if (!select) return;
        const selectedOption = select.options[select.selectedIndex];
        const roleName = selectedOption ? selectedOption.text.trim().toLowerCase() : '';

        const divTelefono = document.getElementById('edit-div-telefono');
        const divDireccion = document.getElementById('edit-div-direccion');
        const divCargo = document.getElementById('edit-div-cargo');
        const divArea = document.getElementById('edit-div-area');

        if (!divTelefono || !divDireccion || !divCargo || !divArea) return;

        const inputTelefono = divTelefono.querySelector('input');
        const inputDireccion = divDireccion.querySelector('input');
        const inputCargo = divCargo.querySelector('input');
        const inputArea = divArea.querySelector('input');

        if (roleName === 'superadministrador' || roleName === 'admin') {
            divTelefono.classList.remove('hidden');
            divDireccion.classList.remove('hidden');
            divCargo.classList.remove('hidden');
            divArea.classList.remove('hidden');

            inputTelefono.disabled = false;
            inputDireccion.disabled = false;
            inputCargo.disabled = false;
            inputArea.disabled = false;
        } else if (roleName === 'docente') {
            divTelefono.classList.remove('hidden');
            divDireccion.classList.add('hidden');
            divCargo.classList.add('hidden');
            divArea.classList.add('hidden');

            inputTelefono.disabled = false;
            inputDireccion.disabled = true;
            inputCargo.disabled = true;
            inputArea.disabled = true;
        } else if (roleName === 'postulante') {
            divTelefono.classList.add('hidden');
            divDireccion.classList.add('hidden');
            divCargo.classList.add('hidden');
            divArea.classList.add('hidden');

            inputTelefono.disabled = true;
            inputDireccion.disabled = true;
            inputCargo.disabled = true;
            inputArea.disabled = true;
        }
    }

    // Populate and open edit user modal
    function openEditModal(button) {
        const user = JSON.parse(button.getAttribute('data-user'));
        if (!user) return;

        const form = document.getElementById('form-editar-usuario');
        if (!form) return;
        form.action = '/admin/usuarios/' + user.id_usuario;

        // Base fields
        document.getElementById('edit-ci').value = user.persona.ci || '';
        document.getElementById('edit-nombre').value = user.persona.nombre || '';
        document.getElementById('edit-apellido').value = user.persona.apellido || '';
        document.getElementById('edit-fecha_nacimiento').value = user.persona.fecha_nacimiento || '';
        document.getElementById('edit-correo').value = user.persona.correo || '';
        document.getElementById('edit-nombre_usuario').value = user.nombre_usuario || '';
        document.getElementById('edit-contraseña').value = ''; // Contraseña optional

        // Dropdown selection
        const select = document.getElementById('edit-modal-id-rol');
        if (select) {
            select.value = user.id_rol;
        }

        // Details fields
        const telefonoInput = document.getElementById('edit-telefono');
        const direccionInput = document.getElementById('edit-direccion');
        const cargoInput = document.getElementById('edit-cargo');
        const areaInput = document.getElementById('edit-area');

        if (telefonoInput) telefonoInput.value = user.persona.telefono || '';
        if (direccionInput) direccionInput.value = user.persona.direccion || '';

        // If target role is superadmin, we look at persona.administrador (same details table as Admin)
        const hasAdminDetails = user.persona.administrador;
        
        if (hasAdminDetails && cargoInput) {
            cargoInput.value = user.persona.administrador.cargo || '';
        } else if (cargoInput) {
            cargoInput.value = '';
        }

        if (hasAdminDetails && areaInput) {
            areaInput.value = user.persona.administrador.area || '';
        } else if (areaInput) {
            areaInput.value = '';
        }

        // Trigger dynamic field toggle
        handleEditRoleChange();

        // Reveal modal
        const modal = document.getElementById('modal-editar-usuario');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Reset and open create user modal
    function openCreateModal() {
        const modal = document.getElementById('modal-usuario');
        if (modal) {
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
                const usernameInput = form.querySelector('input[name="nombre_usuario"]');
                const passwordInput = form.querySelector('input[name="contraseña"]');
                if (usernameInput) usernameInput.value = '';
                if (passwordInput) passwordInput.value = '';
            }
            handleRoleChange(); // Update input visibility dynamically based on default role
            modal.classList.remove('hidden');
        }
    }

    // Initialize layout when page is ready
    document.addEventListener('DOMContentLoaded', () => {
        handleRoleChange();
    });
</script>
@endsection
