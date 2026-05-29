@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Admisión</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Gestiona el proceso de inscripción, documentos y consulta del estado del postulante.</p>
        </div>
        <button onclick="openCreateModal()" class="px-5 py-3 bg-[#0066ff] hover:bg-[#0052cc] text-white rounded-2xl font-bold text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2">
            <i class="fa-solid fa-circle-plus text-sm"></i>
            <span>Registrar Postulante</span>
        </button>
    </div>

    <!-- Sub-tabs within Admission -->
    <div class="flex items-center gap-1 bg-white px-8 py-4 rounded-3xl border border-slate-100 shadow-sm mb-6 overflow-x-auto">
        <a href="{{ route('admin.inscripciones') }}" class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-blue-600 text-blue-600 font-extrabold text-sm transition-all focus:outline-none whitespace-nowrap">
            <i class="fa-solid fa-list-check text-blue-500"></i>
            <span>Lista de Postulantes</span>
        </a>
        @php $firstIns = $inscripciones->first(); @endphp
        @if($firstIns)
            <a href="{{ route('admin.inscripciones.detail', $firstIns->id_inscripcion) }}?tab=inscripcion" class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-transparent text-slate-500 font-bold text-sm hover:text-blue-600 hover:border-blue-300 transition-all whitespace-nowrap">
                <i class="fa-solid fa-user-pen"></i>
                <span>Inscripción</span>
            </a>
            <a href="{{ route('admin.inscripciones.detail', $firstIns->id_inscripcion) }}?tab=documentos" class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-transparent text-slate-500 font-bold text-sm hover:text-blue-600 hover:border-blue-300 transition-all whitespace-nowrap">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Documentos</span>
            </a>
            <a href="{{ route('admin.inscripciones.detail', $firstIns->id_inscripcion) }}?tab=pagos" class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-transparent text-slate-500 font-bold text-sm hover:text-blue-600 hover:border-blue-300 transition-all whitespace-nowrap">
                <i class="fa-solid fa-credit-card"></i>
                <span>Pago</span>
            </a>
        @else
            <span class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-transparent text-slate-300 font-bold text-sm cursor-not-allowed whitespace-nowrap" title="Registra primero un postulante">
                <i class="fa-solid fa-user-pen"></i>
                <span>Inscripción</span>
            </span>
            <span class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-transparent text-slate-300 font-bold text-sm cursor-not-allowed whitespace-nowrap" title="Registra primero un postulante">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Documentos</span>
            </span>
            <span class="flex items-center space-x-2 px-5 pb-3 border-b-2 border-transparent text-slate-300 font-bold text-sm cursor-not-allowed whitespace-nowrap" title="Registra primero un postulante">
                <i class="fa-solid fa-credit-card"></i>
                <span>Pago</span>
            </span>
        @endif
    </div>

    <!-- Card Container for Search and Tables -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-6">
        
        <!-- Search bar, Filter, and Export -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('admin.inscripciones') }}" method="GET" class="relative flex-1 max-w-md">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, CI o id_postulante..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-700 placeholder-slate-400 transition-all">
                @if(request('search'))
                    <a href="{{ route('admin.inscripciones') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                        <i class="fa-solid fa-circle-xmark text-sm"></i>
                    </a>
                @endif
            </form>
            <div class="flex items-center space-x-3">
                <button class="flex items-center space-x-2 px-4 py-2.5 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i class="fa-solid fa-sliders text-xs text-slate-500"></i>
                    <span>Filtrar</span>
                </button>
                <button onclick="window.print()" class="flex items-center space-x-2 px-4 py-2.5 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition-all shadow-sm">
                    <i class="fa-solid fa-download text-xs text-slate-500"></i>
                    <span>Exportar</span>
                </button>
            </div>
        </div>

        <!-- Inscriptions List Table -->
        <div class="overflow-hidden">
            <div class="overflow-x-auto rounded-2xl border border-slate-100">
                <table class="w-full text-left border-collapse" id="table-inscripciones">
                    <thead>
                        <tr class="bg-slate-50 text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                            <th class="py-4 px-6">ID_POSTULANTE</th>
                            <th class="py-4 px-6">NOMBRE POSTULANTE</th>
                            <th class="py-4 px-6">INSCRIPCIÓN</th>
                            <th class="py-4 px-6">DOCUMENTOS</th>
                            <th class="py-4 px-6">PAGO</th>
                            <th class="py-4 px-6 text-center">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                        @forelse($inscripciones as $ins)
                            @php
                                $postulante = $ins->postulante;
                                $persona = $postulante->persona;
                                
                                // Calculate Documents state dynamically
                                $docs = $postulante->documentos;
                                $totalDocsCount = $docs->count();
                                $approvedDocsCount = $docs->where('estado', 'Aprobado')->count();
                                $reviewDocsCount = $docs->where('estado', 'En revisión')->count();
                                $rejectedDocsCount = $docs->where('estado', 'Rechazado')->count();
                                
                                $docsStatus = 'Pendiente';
                                $docsColorClass = 'bg-slate-100 text-slate-500';
                                
                                if ($totalDocsCount > 0) {
                                    if ($approvedDocsCount === 7) { // 7 is expected number
                                        $docsStatus = 'Completo';
                                        $docsColorClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                    } elseif ($rejectedDocsCount > 0) {
                                        $docsStatus = 'Observado';
                                        $docsColorClass = 'bg-rose-50 text-rose-700 border border-rose-100';
                                    } elseif ($reviewDocsCount > 0) {
                                        $docsStatus = 'En revisión';
                                        $docsColorClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                                    } else {
                                        $docsStatus = 'En proceso';
                                        $docsColorClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                    }
                                }
                                
                                // Calculate Payments state dynamically
                                $pagos = $ins->pagos;
                                $pago = $pagos->sortByDesc('id_pago')->first();
                                
                                $pagoStatus = '-';
                                $pagoColorClass = 'text-slate-400';
                                
                                if ($pago) {
                                    if ($pago->estado_pago === 'Pagado') {
                                        $pagoStatus = 'Pagado';
                                        $pagoColorClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                    } elseif ($pago->estado_pago === 'Pendiente') {
                                        $pagoStatus = 'Pendiente';
                                        $pagoColorClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                    } else {
                                        $pagoStatus = 'Rechazado';
                                        $pagoColorClass = 'bg-rose-50 text-rose-700 border border-rose-100';
                                    }
                                }
                                
                                // Calculate Inscription status
                                $insStatus = 'Pendiente';
                                $insColorClass = 'bg-slate-100 text-slate-500';
                                
                                if ($ins->estado === 'Validado') {
                                    $insStatus = 'Completa';
                                    $insColorClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                } elseif ($ins->estado === 'Activo') {
                                    if ($docsStatus === 'Completo' && $pagoStatus === 'Pagado') {
                                        $insStatus = 'Completa';
                                        $insColorClass = 'bg-emerald-50 text-emerald-700 border border-emerald-100';
                                    } elseif ($totalDocsCount > 0 || $pago) {
                                        $insStatus = 'En revisión';
                                        $insColorClass = 'bg-amber-50 text-amber-700 border border-amber-100';
                                    } else {
                                        $insStatus = 'En proceso';
                                        $insColorClass = 'bg-blue-50 text-blue-700 border border-blue-100';
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-blue-50/40 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.inscripciones.detail', $ins->id_inscripcion) }}'">
                                <td class="py-4 px-6 font-extrabold text-slate-800 group-hover:text-blue-700">
                                    {{ $ins->codigo_inscripcion }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-[10px] flex-shrink-0">
                                            {{ strtoupper(substr($persona->nombre, 0, 1) . substr($persona->apellido, 0, 1)) }}
                                        </div>
                                        <span class="font-extrabold text-slate-800 group-hover:text-blue-700">{{ $persona->nombre_completo }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col space-y-1">
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-flex items-center gap-1.5 w-fit {{ $insColorClass }}">
                                            @if($insStatus === 'Completa')
                                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            @else
                                                <i class="fa-solid fa-clock text-[10px]"></i>
                                            @endif
                                            {{ $insStatus }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-semibold pl-1">
                                            {{ \Carbon\Carbon::parse($ins->fecha_inscripcion)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col space-y-1">
                                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-flex items-center gap-1.5 w-fit {{ $docsColorClass }}">
                                            @if($docsStatus === 'Completo')
                                                <i class="fa-solid fa-circle-check text-[10px]"></i>
                                            @elseif($docsStatus === 'Observado')
                                                <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                            @else
                                                <i class="fa-solid fa-clock text-[10px]"></i>
                                            @endif
                                            {{ $docsStatus }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-semibold pl-1 font-mono">
                                            @if($docs->count() > 0 && $docs->sortByDesc('fecha_registro')->first())
                                                {{ \Carbon\Carbon::parse($docs->sortByDesc('fecha_registro')->first()->fecha_registro)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col space-y-1">
                                        @if($pagoStatus === '-')
                                            <span class="text-slate-400 font-bold pl-1">-</span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-flex items-center gap-1.5 w-fit {{ $pagoColorClass }}">
                                                @if($pagoStatus === 'Pagado')
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                    Generado
                                                @else
                                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                                    Pendiente
                                                @endif
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-semibold pl-1">
                                                @if($pago->fecha_pago)
                                                    {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                                                @elseif($pago->fecha_registro)
                                                    {{ \Carbon\Carbon::parse($pago->fecha_registro)->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center" onclick="event.stopPropagation()">
                                    <div class="flex items-center justify-center space-x-1">
                                        <!-- Ver Detalle -->
                                        <a href="{{ route('admin.inscripciones.detail', $ins->id_inscripcion) }}" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-[10px] font-bold transition-colors flex items-center gap-1" title="Ver Detalle">
                                            <i class="fa-solid fa-eye text-[10px]"></i>
                                            Ver
                                        </a>
                                        
                                        <!-- Actions Dropdown Trigger -->
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <button @click.stop="open = !open" @click.away="open = false" class="w-8 h-8 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-lg flex items-center justify-center transition-colors" title="Más acciones">
                                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                            </button>
                                            
                                            <!-- Dropdown Menu -->
                                            <div x-show="open" 
                                                 x-transition:enter="transition ease-out duration-100" 
                                                 x-transition:enter-start="transform opacity-0 scale-95" 
                                                 x-transition:enter-end="transform opacity-100 scale-100" 
                                                 x-transition:leave="transition ease-in duration-75" 
                                                 x-transition:leave-start="transform opacity-100 scale-100" 
                                                 x-transition:leave-end="transform opacity-0 scale-95" 
                                                 class="absolute right-0 mt-2 w-52 rounded-xl shadow-xl bg-white ring-1 ring-slate-200 z-20 focus:outline-none divide-y divide-slate-100" 
                                                 style="display: none;">
                                                <div class="py-1">
                                                    <a href="{{ route('admin.inscripciones.detail', $ins->id_inscripcion) }}?tab=inscripcion" class="group flex items-center px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                        <i class="fa-solid fa-user-pen mr-2.5 text-slate-400 group-hover:text-blue-500"></i>
                                                        Ver Inscripción
                                                    </a>
                                                    <a href="{{ route('admin.inscripciones.detail', $ins->id_inscripcion) }}?tab=documentos" class="group flex items-center px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                        <i class="fa-solid fa-file-invoice mr-2.5 text-slate-400 group-hover:text-blue-500"></i>
                                                        Ver Documentos
                                                    </a>
                                                    <a href="{{ route('admin.inscripciones.detail', $ins->id_inscripcion) }}?tab=pagos" class="group flex items-center px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                        <i class="fa-solid fa-credit-card mr-2.5 text-slate-400 group-hover:text-blue-500"></i>
                                                        Ver Estado de Pago
                                                    </a>
                                                    <button onclick="event.stopPropagation(); openEditModal({{ json_encode(['id_inscripcion' => $ins->id_inscripcion, 'ci' => $persona->ci, 'nombre' => $persona->nombre, 'apellido' => $persona->apellido, 'fecha_nacimiento' => $persona->fecha_nacimiento, 'correo' => $persona->correo, 'telefono' => $persona->telefono, 'direccion' => $persona->direccion, 'carrera_principal' => $ins->carreras->where('pivot.prioridad', 1)->first() ? $ins->carreras->where('pivot.prioridad', 1)->first()->id_carrera : '', 'carrera_secundaria' => $ins->carreras->where('pivot.prioridad', 2)->first() ? $ins->carreras->where('pivot.prioridad', 2)->first()->id_carrera : '']) }})" class="w-full text-left group flex items-center px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                                        <i class="fa-solid fa-pen-to-square mr-2.5 text-slate-400 group-hover:text-amber-500"></i>
                                                        Editar Datos
                                                    </button>
                                                </div>
                                                <div class="py-1">
                                                    @if($ins->estado !== 'Validado')
                                                    <form action="{{ route('admin.inscripciones.validate', $ins->id_inscripcion) }}" method="POST" class="w-full">
                                                        @csrf
                                                        <button type="submit" onclick="event.stopPropagation()" class="w-full text-left group flex items-center px-4 py-2.5 text-xs font-semibold text-emerald-600 hover:bg-emerald-50">
                                                            <i class="fa-solid fa-user-check mr-2.5 text-emerald-400 group-hover:text-emerald-600"></i>
                                                            Validar Datos
                                                        </button>
                                                    </form>
                                                    @endif
                                                    
                                                    @if(!$pago)
                                                    <form action="{{ route('admin.inscripciones.payment', $ins->id_inscripcion) }}" method="POST" class="w-full">
                                                        @csrf
                                                        <button type="submit" onclick="event.stopPropagation()" class="w-full text-left group flex items-center px-4 py-2.5 text-xs font-semibold text-blue-600 hover:bg-blue-50">
                                                            <i class="fa-solid fa-file-invoice-dollar mr-2.5 text-blue-400 group-hover:text-blue-600"></i>
                                                            Generar Orden de Pago
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                                <div class="py-1">
                                                    <form action="{{ route('admin.inscripciones.destroy', $ins->id_inscripcion) }}" method="POST" onsubmit="return confirm('¿Está seguro de eliminar esta inscripción y la persona/usuario asociada?')" class="w-full">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="event.stopPropagation()" class="w-full text-left group flex items-center px-4 py-2.5 text-xs font-semibold text-rose-600 hover:bg-rose-50">
                                                            <i class="fa-solid fa-trash mr-2.5 text-rose-400 group-hover:text-rose-600"></i>
                                                            Eliminar Registro
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-450 font-bold">No hay postulantes registrados con este criterio.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer / Pagination -->
            <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-slate-100 mt-4 text-xs font-semibold text-slate-450">
                <span>Mostrando 1 a {{ $inscripciones->count() }} de {{ $inscripciones->count() }} postulantes</span>
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

    </div>

</div>

<!-- ------------------------------------------------------------- -->
<!-- MODAL: REGISTRAR POSTULANTE                                   -->
<!-- ------------------------------------------------------------- -->
<div id="modal-create-inscripcion" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-create-card">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-650">
                    <i class="fa-solid fa-user-plus text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Registrar Inscripción de Postulante</h3>
                    <p class="text-[10px] text-slate-400 font-semibold">Crea un nuevo perfil de postulante y asocia sus carreras.</p>
                </div>
            </div>
            <button onclick="closeCreateModal()" class="w-8 h-8 hover:bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form action="{{ route('admin.inscripciones.store') }}" method="POST" class="flex-1 overflow-y-auto p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CI -->
                <div class="space-y-1.5">
                    <label for="ci" class="text-xs font-bold text-slate-700">Cédula de Identidad (CI) *</label>
                    <input type="text" name="ci" id="ci" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Ej. 8765432">
                </div>

                <!-- Correo -->
                <div class="space-y-1.5">
                    <label for="correo" class="text-xs font-bold text-slate-700">Correo Electrónico *</label>
                    <input type="email" name="correo" id="correo" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Ej. juan.perez@email.com">
                </div>

                <!-- Nombre -->
                <div class="space-y-1.5">
                    <label for="nombre" class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                    <input type="text" name="nombre" id="nombre" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Ej. Juan">
                </div>

                <!-- Apellido -->
                <div class="space-y-1.5">
                    <label for="apellido" class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                    <input type="text" name="apellido" id="apellido" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Ej. Pérez Soria">
                </div>

                <!-- Fecha Nacimiento -->
                <div class="space-y-1.5">
                    <label for="fecha_nacimiento" class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Telefono -->
                <div class="space-y-1.5">
                    <label for="telefono" class="text-xs font-bold text-slate-700">Teléfono / Celular</label>
                    <input type="text" name="telefono" id="telefono" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Ej. 76543210">
                </div>

                <!-- Direccion -->
                <div class="col-span-full space-y-1.5">
                    <label for="direccion" class="text-xs font-bold text-slate-700">Dirección de Domicilio</label>
                    <input type="text" name="direccion" id="direccion" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800 placeholder-slate-350" placeholder="Ej. Av. Las Flores Nro 123">
                </div>

                <!-- Carrera Principal -->
                <div class="space-y-1.5">
                    <label for="carrera_principal_id" class="text-xs font-bold text-slate-700">Carrera Principal *</label>
                    <select name="carrera_principal_id" id="carrera_principal_id" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="">Seleccione carrera</option>
                        @foreach($carreras as $c)
                            <option value="{{ $c->id_carrera }}">{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Carrera Secundaria -->
                <div class="space-y-1.5">
                    <label for="carrera_secundaria_id" class="text-xs font-bold text-slate-700">Carrera Secundaria *</label>
                    <select name="carrera_secundaria_id" id="carrera_secundaria_id" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="">Seleccione carrera</option>
                        @foreach($carreras as $c)
                            <option value="{{ $c->id_carrera }}">{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Modal Actions Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3 flex-shrink-0">
                <button type="button" onclick="closeCreateModal()" class="px-5 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-[#0066ff] hover:bg-[#0052cc] text-white rounded-xl font-bold text-xs shadow-md transition-all">Registrar Postulante</button>
            </div>
        </form>
    </div>
</div>

<!-- ------------------------------------------------------------- -->
<!-- MODAL: EDITAR POSTULANTE                                     -->
<!-- ------------------------------------------------------------- -->
<div id="modal-edit-inscripcion" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden transform transition-all duration-300 scale-95 opacity-0" id="modal-edit-card">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-650">
                    <i class="fa-solid fa-user-pen text-base"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800">Modificar Datos de Inscripción</h3>
                    <p class="text-[10px] text-slate-400 font-semibold">Edita los datos personales y preferencias del postulante.</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 hover:bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 hover:text-slate-650 transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>
        
        <!-- Modal Form -->
        <form id="form-edit-inscripcion" method="POST" class="flex-1 overflow-y-auto p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CI -->
                <div class="space-y-1.5">
                    <label for="edit_ci" class="text-xs font-bold text-slate-700">Cédula de Identidad (CI) *</label>
                    <input type="text" name="ci" id="edit_ci" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Correo -->
                <div class="space-y-1.5">
                    <label for="edit_correo" class="text-xs font-bold text-slate-700">Correo Electrónico *</label>
                    <input type="email" name="correo" id="edit_correo" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Nombre -->
                <div class="space-y-1.5">
                    <label for="edit_nombre" class="text-xs font-bold text-slate-700">Nombre(s) *</label>
                    <input type="text" name="nombre" id="edit_nombre" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Apellido -->
                <div class="space-y-1.5">
                    <label for="edit_apellido" class="text-xs font-bold text-slate-700">Apellido(s) *</label>
                    <input type="text" name="apellido" id="edit_apellido" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Fecha Nacimiento -->
                <div class="space-y-1.5">
                    <label for="edit_fecha_nacimiento" class="text-xs font-bold text-slate-700">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" id="edit_fecha_nacimiento" required class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Telefono -->
                <div class="space-y-1.5">
                    <label for="edit_telefono" class="text-xs font-bold text-slate-700">Teléfono / Celular</label>
                    <input type="text" name="telefono" id="edit_telefono" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Direccion -->
                <div class="col-span-full space-y-1.5">
                    <label for="edit_direccion" class="text-xs font-bold text-slate-700">Dirección de Domicilio</label>
                    <input type="text" name="direccion" id="edit_direccion" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                </div>

                <!-- Carrera Principal -->
                <div class="space-y-1.5">
                    <label for="edit_carrera_principal" class="text-xs font-bold text-slate-700">Carrera Principal *</label>
                    <select name="carrera_principal_id" id="edit_carrera_principal" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="">Seleccione carrera</option>
                        @foreach($carreras as $c)
                            <option value="{{ $c->id_carrera }}">{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Carrera Secundaria -->
                <div class="space-y-1.5">
                    <label for="edit_carrera_secundaria" class="text-xs font-bold text-slate-700">Carrera Secundaria *</label>
                    <select name="carrera_secundaria_id" id="edit_carrera_secundaria" required class="block w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs font-semibold text-slate-800">
                        <option value="">Seleccione carrera</option>
                        @foreach($carreras as $c)
                            <option value="{{ $c->id_carrera }}">{{ $c->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Modal Actions Footer -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3 flex-shrink-0">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 border border-slate-200 hover:bg-slate-50 text-slate-500 rounded-xl font-bold text-xs transition-all">Cancelar</button>
                <button type="submit" class="px-5 py-3 bg-[#0066ff] hover:bg-[#0052cc] text-white rounded-xl font-bold text-xs shadow-md transition-all">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Create Modal Actions
    function openCreateModal() {
        const modal = document.getElementById('modal-create-inscripcion');
        const card = document.getElementById('modal-create-card');
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeCreateModal() {
        const modal = document.getElementById('modal-create-inscripcion');
        const card = document.getElementById('modal-create-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Edit Modal Actions
    function openEditModal(data) {
        const modal = document.getElementById('modal-edit-inscripcion');
        const card = document.getElementById('modal-edit-card');
        
        // Fill form fields
        document.getElementById('edit_ci').value = data.ci || '';
        document.getElementById('edit_correo').value = data.correo || '';
        document.getElementById('edit_nombre').value = data.nombre || '';
        document.getElementById('edit_apellido').value = data.apellido || '';
        document.getElementById('edit_fecha_nacimiento').value = data.fecha_nacimiento || '';
        document.getElementById('edit_telefono').value = data.telefono || '';
        document.getElementById('edit_direccion').value = data.direccion || '';
        document.getElementById('edit_carrera_principal').value = data.carrera_principal || '';
        document.getElementById('edit_carrera_secundaria').value = data.carrera_secundaria || '';
        
        // Set action url
        const form = document.getElementById('form-edit-inscripcion');
        form.action = `/admin/inscripciones/${data.id_inscripcion}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            card.classList.remove('scale-95', 'opacity-0');
            card.classList.add('scale-100', 'opacity-100');
        }, 50);
    }
    
    function closeEditModal() {
        const modal = document.getElementById('modal-edit-inscripcion');
        const card = document.getElementById('modal-edit-card');
        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }
</script>
@endsection
