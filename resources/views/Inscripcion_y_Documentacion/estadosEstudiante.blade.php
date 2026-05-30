@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Page Header and Breadcrumb -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Estado del Postulante</h1>
            <nav class="flex text-sm text-slate-400 mt-1">
                <a href="{{ route('dashboard') }}" class="hover:text-red-500 transition-colors">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-slate-600 font-medium">Postulantes</span>
                <span class="mx-2">/</span>
                <span class="text-slate-600 font-medium">Estado del Postulante</span>
            </nav>
        </div>
    </div>

    <!-- MAIN CARD: CONSULTAR ESTADO DEL POSTULANTE -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-800">Consultar Estado del Postulante</h2>
            <p class="text-xs text-slate-400 mt-1">Seleccione o ingrese el código del postulante para consultar su estado actual.</p>
        </div>

        <!-- Search Forms -->
        <form action="{{ route('admin.estado_postulante') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
            
            <!-- ComboBox dropdown selection -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Seleccionar Postulante</label>
                <select name="id_postulante" onchange="this.form.submit()"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all">
                    <option value="">-- Seleccionar de la lista --</option>
                    @foreach($postulantesList as $item)
                        <option value="{{ $item['id_postulante'] }}" 
                            {{ (isset($postulanteSelected) && $postulanteSelected->id_postulante == $item['id_postulante']) ? 'selected' : '' }}>
                            {{ $item['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Manual code input -->
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Código del Postulante / CI *</label>
                <div class="relative">
                    <input type="text" name="search_code" placeholder="Ej: POS-2024-000125" value="{{ request('search_code') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-4 pr-10 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-red-500/20 focus:border-red-500 outline-none transition-all">
                    @if(request('search_code'))
                        <a href="{{ route('admin.estado_postulante') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Consult button -->
            <button type="submit" class="px-6 py-3 w-full md:w-auto bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-600/10 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>Consultar</span>
            </button>
        </form>

        <!-- Basic student info profile row -->
        @if($postulanteSelected)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 bg-slate-50 border border-slate-100 rounded-2xl p-6">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nombre del Postulante</span>
                    <span class="block text-sm font-bold text-slate-800 mt-1">{{ $postulanteSelected->persona->nombre_completo ?? 'Sin Nombre' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Correo Electrónico</span>
                    <span class="block text-sm font-bold text-slate-800 mt-1">{{ $postulanteSelected->persona->correo ?? 'Sin Correo' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Teléfono</span>
                    <span class="block text-sm font-bold text-slate-800 mt-1">{{ $postulanteSelected->persona->telefono ?? 'Sin Teléfono' }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Carrera de Preferencia</span>
                    <span class="block text-sm font-bold text-slate-800 mt-1">{{ $carrera->nombre_carrera ?? 'Sin Asignar' }}</span>
                </div>
            </div>
        @else
            <div class="bg-rose-50 border border-rose-100 text-rose-800 rounded-xl p-4 flex gap-3 text-sm font-medium">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg mt-0.5"></i>
                <span>No se encontró ningún postulante. Seleccione uno de la lista o ingrese un código válido para visualizar su estado de admisión.</span>
            </div>
        @endif
    </div>

    @if($postulanteSelected)
        <!-- STATES GRID: 4 STATUS CARDS -->
        <div>
            <h3 class="text-md font-bold text-slate-800 mb-4">Estados del Proceso de Admisión</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- 1. Estado de Inscripción -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center flex-shrink-0 text-xl">
                        <i class="fa-regular fa-clipboard"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Estado de Inscripción</span>
                        @if($inscripcion)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                Completado
                            </span>
                            <p class="text-[11px] text-slate-500 font-medium">Inscripción realizada correctamente.</p>
                            <span class="block text-[10px] text-slate-400">Fecha: {{ $inscripcion->fecha_inscripcion }}</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700">
                                Pendiente
                            </span>
                        @endif
                    </div>
                </div>

                <!-- 2. Estado de Documentos -->
                @php
                    $docStatus = 'Pendiente';
                    $docBadge = 'bg-rose-50 text-rose-700';
                    $docText = 'Pendiente de presentación.';
                    $docDate = null;
                    if ($documentos->isNotEmpty()) {
                        $allValid = $documentos->every(fn($d) => $d->estado === 'Validado');
                        $anyRejected = $documentos->contains(fn($d) => $d->estado === 'Rechazado');
                        
                        if ($allValid) {
                            $docStatus = 'Validado';
                            $docBadge = 'bg-emerald-50 text-emerald-700';
                            $docText = 'Todos los documentos han sido validados correctamente.';
                            $docDate = $documentos->first()->fecha_validacion;
                        } elseif ($anyRejected) {
                            $docStatus = 'Rechazado';
                            $docBadge = 'bg-red-50 text-red-700';
                            $docText = 'Uno o más documentos presentan observaciones.';
                        } else {
                            $docStatus = 'En Revisión';
                            $docBadge = 'bg-orange-50 text-orange-700';
                            $docText = 'Documentos en revisión por administración.';
                        }
                    }
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center flex-shrink-0 text-xl">
                        <i class="fa-regular fa-file-lines"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Estado de Documentos</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $docBadge }}">
                            {{ $docStatus }}
                        </span>
                        <p class="text-[11px] text-slate-500 font-medium">{{ $docText }}</p>
                        @if($docDate)
                            <span class="block text-[10px] text-slate-400">Fecha: {{ $docDate }}</span>
                        @endif
                    </div>
                </div>

                <!-- 3. Estado de Pago -->
                @php
                    $pagoStatus = 'Pendiente';
                    $pagoBadge = 'bg-rose-50 text-rose-700';
                    $pagoText = 'Pago de inscripción pendiente.';
                    $pagoDate = null;
                    if ($pagos->isNotEmpty()) {
                        $firstPago = $pagos->first();
                        $pagoStatus = $firstPago->estado_pago;
                        if ($pagoStatus === 'Pagado') {
                            $pagoBadge = 'bg-emerald-50 text-emerald-700';
                            $pagoText = 'El pago de inscripción ha sido realizado correctamente.';
                            $pagoDate = $firstPago->fecha_pago;
                        } elseif ($pagoStatus === 'Rechazado') {
                            $pagoBadge = 'bg-red-50 text-red-700';
                            $pagoText = 'Su comprobante de pago fue rechazado. Verifique observaciones.';
                        } else {
                            $pagoStatus = 'Pendiente';
                            $pagoBadge = 'bg-orange-50 text-orange-700';
                            $pagoText = 'Pago en validación por caja.';
                        }
                    }
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0 text-xl">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Estado de Pago</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $pagoBadge }}">
                            {{ $pagoStatus }}
                        </span>
                        <p class="text-[11px] text-slate-500 font-medium">{{ $pagoText }}</p>
                        @if($pagoDate)
                            <span class="block text-[10px] text-slate-400">Fecha: {{ $pagoDate }}</span>
                        @endif
                    </div>
                </div>

                <!-- 4. Grupo Asignado -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 flex items-start gap-4">
                    <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-xl flex items-center justify-center flex-shrink-0 text-xl">
                        <i class="fa-solid fa-people-group"></i>
                    </div>
                    <div class="space-y-1">
                        <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Grupo Asignado</span>
                        @if($grupo)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700">
                                Asignado
                            </span>
                            <p class="text-[11px] text-slate-800 font-bold">{{ $grupo->sigla_grupo }}</p>
                            <span class="block text-[10px] text-slate-400">Fecha: {{ $grupo->pivot->fecha_asignacion }}</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">
                                Sin Asignar
                            </span>
                            <p class="text-[11px] text-slate-500 font-medium">Asignación pendiente de aprobación de pago.</p>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        <!-- PROGRESS TIMELINE AND HISTORY -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- PROGRESS TIMELINE (Left Column) -->
            <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="text-md font-bold text-slate-800">Resumen del Proceso</h3>
                    <p class="text-xs text-slate-400 mt-1">Línea de progreso del postulante actual.</p>
                </div>

                <!-- Dynamic progress timeline -->
                <div class="relative pl-6 space-y-6 border-l-2 border-slate-100 ml-4 py-2">
                    
                    <!-- Item 1: Inscripción -->
                    <div class="relative">
                        @php
                            $step1Active = (bool)$inscripcion;
                            $dotColor1 = $step1Active ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-slate-400';
                        @endphp
                        <span class="absolute -left-[35px] top-0 flex items-center justify-center w-6 h-6 rounded-full border-2 {{ $dotColor1 }} text-[10px] font-bold">
                            @if($step1Active) <i class="fa-solid fa-check"></i> @else 1 @endif
                        </span>
                        <div class="ml-2">
                            <h4 class="text-xs font-bold text-slate-800">Inscripción</h4>
                            <span class="text-[10px] font-semibold text-slate-400 {{ $step1Active ? 'text-emerald-600' : '' }}">
                                {{ $step1Active ? 'Inscripción Completada' : 'Pendiente' }}
                            </span>
                        </div>
                    </div>

                    <!-- Item 2: Documentos -->
                    <div class="relative">
                        @php
                            $step2Active = ($docStatus === 'Validado');
                            $dotColor2 = $step2Active ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-slate-400';
                        @endphp
                        <span class="absolute -left-[35px] top-0 flex items-center justify-center w-6 h-6 rounded-full border-2 {{ $dotColor2 }} text-[10px] font-bold">
                            @if($step2Active) <i class="fa-solid fa-check"></i> @else 2 @endif
                        </span>
                        <div class="ml-2">
                            <h4 class="text-xs font-bold text-slate-800">Documentos</h4>
                            <span class="text-[10px] font-semibold text-slate-400 {{ $step2Active ? 'text-emerald-600' : '' }}">
                                {{ $docStatus }}
                            </span>
                        </div>
                    </div>

                    <!-- Item 3: Pago -->
                    <div class="relative">
                        @php
                            $step3Active = ($pagoStatus === 'Pagado');
                            $dotColor3 = $step3Active ? 'bg-emerald-500 border-emerald-500 text-white' : 'bg-white border-slate-200 text-slate-400';
                        @endphp
                        <span class="absolute -left-[35px] top-0 flex items-center justify-center w-6 h-6 rounded-full border-2 {{ $dotColor3 }} text-[10px] font-bold">
                            @if($step3Active) <i class="fa-solid fa-check"></i> @else 3 @endif
                        </span>
                        <div class="ml-2">
                            <h4 class="text-xs font-bold text-slate-800">Pago</h4>
                            <span class="text-[10px] font-semibold text-slate-400 {{ $step3Active ? 'text-emerald-600' : '' }}">
                                {{ $pagoStatus }}
                            </span>
                        </div>
                    </div>

                    <!-- Item 4: Grupo Asignado -->
                    <div class="relative">
                        @php
                            $step4Active = (bool)$grupo;
                            $dotColor4 = $step4Active ? 'bg-blue-500 border-blue-500 text-white' : 'bg-white border-slate-200 text-slate-400';
                        @endphp
                        <span class="absolute -left-[35px] top-0 flex items-center justify-center w-6 h-6 rounded-full border-2 {{ $dotColor4 }} text-[10px] font-bold">
                            @if($step4Active) <i class="fa-solid fa-check"></i> @else 4 @endif
                        </span>
                        <div class="ml-2">
                            <h4 class="text-xs font-bold text-slate-800">Grupo Asignado</h4>
                            <span class="text-[10px] font-semibold text-slate-400 {{ $step4Active ? 'text-blue-600' : '' }}">
                                {{ $step4Active ? 'Asignado' : 'Pendiente' }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            <!-- HISTORY LOGS (Right Columns) -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="text-md font-bold text-slate-800">Historial de Estados</h3>
                    <p class="text-xs text-slate-400 mt-1">Registros del proceso de inscripción y validación de este postulante.</p>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Fecha</th>
                                <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-44">Estado</th>
                                <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-5 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700">
                            @forelse($historial as $row)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-4 text-slate-500 text-xs whitespace-nowrap">{{ $row['fecha'] }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold leading-none 
                                            {{ $row['badge'] === 'badge-green' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $row['badge'] === 'badge-green' ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                            {{ $row['estado'] }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-slate-900">{{ $row['descripcion'] }}</td>
                                    <td class="px-5 py-4 text-slate-500 text-xs">{{ $row['observaciones'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center text-slate-400">
                                        <i class="fa-solid fa-clock-rotate-left text-3xl mb-2 text-slate-350"></i>
                                        <p class="font-medium text-sm">No hay registros en el historial de este postulante.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex items-center justify-between text-xs text-slate-400 font-bold border-t border-slate-100 pt-4">
                    <span>Mostrando {{ count($historial) }} de {{ count($historial) }} registros</span>
                </div>

            </div>

        </div>
    @endif

</div>
@endsection
