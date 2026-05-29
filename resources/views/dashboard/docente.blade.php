@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Welcome Header & Time -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dashboard - Docente</h1>
            <p class="text-xs text-slate-400 font-semibold mt-1">Resumen de tus asignaturas y actividades académicas</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-white border border-slate-100 rounded-xl text-xs font-bold text-slate-600 shadow-sm flex items-center">
                <i class="fa-regular fa-calendar-days mr-2 text-slate-400"></i>
                {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
            </span>
            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-100 flex items-center">
                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                Docente Activo
            </span>
        </div>
    </div>

    <!-- KPIs row exactly matching the bottom mockup -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Asignaturas -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mt-4">3</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Asignaturas</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">A tu cargo</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4">
                <a href="#" class="text-[10px] font-bold text-blue-600 hover:text-blue-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Card 2: Grupos -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-people-group"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mt-4">2</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Grupos</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Asignados</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4">
                <a href="#" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Card 3: Postulantes -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mt-4">78</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Postulantes</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">En tus grupos</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4">
                <a href="#" class="text-[10px] font-bold text-amber-600 hover:text-amber-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Card 4: Evaluaciones -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div>
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mt-4">12</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mt-0.5">Evaluaciones</span>
                <p class="text-[9px] text-slate-400 mt-1 font-semibold">Pendientes</p>
            </div>
            <div class="border-t border-slate-100 pt-3 mt-4">
                <a href="#" class="text-[10px] font-bold text-purple-600 hover:text-purple-800 flex items-center space-x-1">
                    <span>Ver detalles</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Section: Mis Asignaturas & side lists exactly matching bottom mockup -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Mis Asignaturas (Table layout) -->
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 lg:col-span-2 flex flex-col justify-between">
            <div>
                <div class="border-b border-slate-100 pb-4 mb-4">
                    <h3 class="text-sm font-bold text-slate-800">Mis Asignaturas</h3>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Horarios y ubicaciones de tus asignaturas asignadas</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-[9px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-100">
                                <th class="py-3 px-4">Asignatura</th>
                                <th class="py-3 px-4">Grupo</th>
                                <th class="py-3 px-4">Horario</th>
                                <th class="py-3 px-4">Aula</th>
                                <th class="py-3 px-4 text-center">Postulantes</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700">
                            <!-- Row 1: Matemáticas -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-800">Matemáticas</td>
                                <td class="py-3.5 px-4">Grupo 1A</td>
                                <td class="py-3.5 px-4 text-slate-500">Lun 08:00 - 10:00</td>
                                <td class="py-3.5 px-4"><span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md font-bold">Aula 101</span></td>
                                <td class="py-3.5 px-4 text-center font-bold">45</td>
                            </tr>
                            <!-- Row 2: Física -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-800">Física</td>
                                <td class="py-3.5 px-4">Grupo 1A</td>
                                <td class="py-3.5 px-4 text-slate-500">Mié 10:00 - 12:00</td>
                                <td class="py-3.5 px-4"><span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md font-bold">Aula 101</span></td>
                                <td class="py-3.5 px-4 text-center font-bold">45</td>
                            </tr>
                            <!-- Row 3: Computación -->
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-4 font-bold text-slate-800">Computación</td>
                                <td class="py-3.5 px-4">Grupo 1B</td>
                                <td class="py-3.5 px-4 text-slate-500">Vie 08:00 - 10:00</td>
                                <td class="py-3.5 px-4"><span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md font-bold">Laboratorio 2</span></td>
                                <td class="py-3.5 px-4 text-center font-bold">33</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 mt-6 text-right">
                <a href="#" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center justify-end space-x-1">
                    <span>Ver todas mis asignaturas</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Right: Evaluaciones Pendientes & Próximas Actividades -->
        <div class="space-y-6">
            <!-- Evaluaciones Pendientes -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="text-sm font-bold text-slate-800">Evaluaciones Pendientes</h3>
                    <button class="text-[10px] font-bold text-slate-400 hover:text-slate-600">Ver todos</button>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-2xl border border-slate-100/50">
                        <span class="text-xs font-bold text-slate-700 flex items-center">
                            <i class="fa-solid fa-file-pen text-blue-500 mr-2.5"></i>
                            Grupo 1A - Matemáticas
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100">8 pendientes</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-2xl border border-slate-100/50">
                        <span class="text-xs font-bold text-slate-700 flex items-center">
                            <i class="fa-solid fa-file-pen text-emerald-500 mr-2.5"></i>
                            Grupo 1A - Física
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100">2 pendientes</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-2xl border border-slate-100/50">
                        <span class="text-xs font-bold text-slate-700 flex items-center">
                            <i class="fa-solid fa-file-pen text-purple-500 mr-2.5"></i>
                            Grupo 1B - Computación
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-amber-50 text-amber-600 border border-amber-100">2 pendientes</span>
                    </div>
                </div>
            </div>

            <!-- Próximas Actividades -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="border-b border-slate-100 pb-3 mb-4">
                        <h3 class="text-sm font-bold text-slate-800">Próximas Actividades</h3>
                    </div>
                    <div class="space-y-3.5">
                        <div class="flex items-start space-x-3 text-xs">
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-[10px] mt-0.5">
                                <i class="fa-solid fa-circle-play"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Matemáticas - Evaluación 1</h4>
                                <p class="text-[9px] text-slate-400 font-semibold mt-0.5">30/05/2026 - 08:00 AM</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 text-xs">
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-[10px] mt-0.5">
                                <i class="fa-solid fa-circle-play"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Física - Evaluación 1</h4>
                                <p class="text-[9px] text-slate-400 font-semibold mt-0.5">31/05/2026 - 08:00 AM</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 text-xs">
                            <div class="w-6 h-6 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 text-[10px] mt-0.5">
                                <i class="fa-solid fa-circle-play"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Computación - Práctica 1</h4>
                                <p class="text-[9px] text-slate-400 font-semibold mt-0.5">02/06/2026 - 08:00 AM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-6 text-right">
                    <a href="#" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-800 flex items-center justify-end space-x-1">
                        <span>Ver calendario completo</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Real Database Groups management (Tus Grupos Asignados list) -->
    <div>
        <h2 class="text-lg font-bold text-slate-800">Panel de Calificaciones y Asistencias en Tiempo Real</h2>
        <p class="text-xs text-slate-400 font-semibold mt-0.5">Gestión en base de datos PostgreSQL de tus asignaciones asignadas para el examen CUP</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($grupos as $g)
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex flex-col justify-between hover:shadow-md transition-shadow">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-1 rounded-full text-[9px] font-bold inline-block bg-emerald-50 text-emerald-600 border border-emerald-100">
                            Grupo CUP
                        </span>
                        <span class="text-[10px] font-bold text-slate-400">Gestión: {{ $g->gestion->anio }}</span>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-800 mt-4">{{ $g->sigla_grupo }}</h3>
                    <p class="text-xs text-slate-400 font-semibold mt-1">Aula: {{ $g->aula->codigo_aula }} ({{ $g->aula->ubicacion }})</p>
                    
                    <div class="mt-4 flex items-center space-x-2 text-xs font-semibold text-slate-600">
                        <i class="fa-regular fa-clock text-slate-400"></i>
                        <span>Turno: {{ $g->turno->nombre_turno }} ({{ $g->turno->hora_inicio }} - {{ $g->turno->hora_fin }})</span>
                    </div>

                    <!-- Students count progress -->
                    <div class="mt-6">
                        <div class="flex items-center justify-between text-xs font-semibold text-slate-600 mb-1.5">
                            <span>Postulantes Asignados</span>
                            <span class="font-bold text-slate-800">{{ $g->cant_estudiantes }} / {{ $g->capacidad_max }}</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-emerald-600 h-1.5 rounded-full" style="width: {{ ($g->cant_estudiantes / $g->capacidad_max) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 mt-6">
                    <a href="{{ route('docente.grupo.view', $g->id_grupo) }}" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 px-4 rounded-xl font-bold text-xs shadow-md transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-folder-open"></i>
                        <span>Gestionar Notas y Asistencias</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-3xl border border-slate-100">
                <i class="fa-solid fa-users-slash text-4xl text-slate-300 mb-3 block"></i>
                <span class="text-xs font-semibold">No tienes grupos asignados para esta gestión académica en PostgreSQL.</span>
            </div>
        @endforelse
    </div>

</div>
@endsection
