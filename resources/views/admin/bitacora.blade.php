@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Title Section -->
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Bitácora de Auditoría del Sistema</h1>
        <p class="text-xs text-slate-400 font-semibold mt-1">Monitorea y audita de forma cronológica todas las acciones y modificaciones realizadas en el sistema.</p>
    </div>

    <!-- Timeline of Log Actions -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden p-6 md:p-8">
        <div class="relative border-l-2 border-slate-100 ml-4 md:ml-6 space-y-8">
            @forelse($logs as $log)
                <div class="relative pl-8 md:pl-10">
                    <!-- Dynamic Circle Indicator with icon depending on type -->
                    <div class="absolute -left-[17px] top-0 w-8 h-8 rounded-full flex items-center justify-center text-xs shadow border-2 border-white
                        @if($log->tipo === 'AUTH') bg-blue-500 text-white
                        @elseif($log->tipo === 'CREATE') bg-emerald-500 text-white
                        @elseif($log->tipo === 'UPDATE') bg-amber-500 text-white
                        @elseif($log->tipo === 'DELETE') bg-rose-500 text-white
                        @else bg-slate-500 text-white @endif">
                        
                        @if($log->tipo === 'AUTH') <i class="fa-solid fa-key"></i>
                        @elseif($log->tipo === 'CREATE') <i class="fa-solid fa-plus"></i>
                        @elseif($log->tipo === 'UPDATE') <i class="fa-solid fa-pen"></i>
                        @elseif($log->tipo === 'DELETE') <i class="fa-solid fa-trash-can"></i>
                        @else <i class="fa-solid fa-sliders"></i> @endif
                    </div>

                    <!-- Header of the Log node -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-2">
                        <div class="flex items-center space-x-3">
                            <span class="text-xs font-bold px-2 py-0.5 rounded
                                @if($log->tipo === 'AUTH') bg-blue-50 text-blue-600
                                @elseif($log->tipo === 'CREATE') bg-emerald-50 text-emerald-600
                                @elseif($log->tipo === 'UPDATE') bg-amber-50 text-amber-600
                                @elseif($log->tipo === 'DELETE') bg-rose-50 text-rose-600
                                @else bg-slate-50 text-slate-600 @endif">
                                {{ $log->tipo }}
                            </span>
                            <h4 class="text-sm font-extrabold text-slate-800">{{ $log->descripcion }}</h4>
                        </div>
                        <div class="flex items-center space-x-2 text-[10px] text-slate-400 font-bold">
                            <i class="fa-regular fa-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($log->fecha)->format('d/m/Y') }} a las {{ $log->hora }}</span>
                        </div>
                    </div>

                    <!-- Details of action -->
                    @if($log->detalles && $log->detalles->count() > 0)
                        @foreach($log->detalles as $det)
                            <div class="mt-3 bg-slate-50 border border-slate-100 rounded-2xl p-4 text-xs font-semibold text-slate-600 max-w-4xl space-y-2">
                                <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                                    <div class="flex items-center space-x-2 text-[10px] text-slate-400 font-bold">
                                        <i class="fa-solid fa-network-wired text-xs"></i>
                                        <span>IP: {{ $det->direccion_ip }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-[10px] text-slate-400 font-bold">
                                        <i class="fa-solid fa-user-gear"></i>
                                        <span>Ejecutado por: {{ $log->usuario->persona->nombre_completo ?? 'Sistema' }} (Username: {{ $log->usuario->nombre_usuario }})</span>
                                    </div>
                                </div>
                                <div class="font-mono text-[10px] bg-white border border-slate-100 p-2.5 rounded-lg overflow-x-auto text-slate-700 select-all leading-normal whitespace-pre-wrap">
                                    {{ $det->accion }}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @empty
                <div class="py-12 text-center text-slate-400">No hay acciones registradas en la bitácora todavía.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
