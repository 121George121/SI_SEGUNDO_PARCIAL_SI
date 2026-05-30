<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usuario_Seguridad_y_Auditoria\autenticacionController;
use App\Http\Controllers\Usuario_Seguridad_y_Auditoria\recuperacionController;
use App\Http\Controllers\Usuario_Seguridad_y_Auditoria\usuariosYRolesController;
use App\Http\Controllers\Inscripcion_y_Documentacion\InscripcionController;
use App\Http\Controllers\Inscripcion_y_Documentacion\documentosController;
use App\Http\Controllers\Gestion_Academica\CarrerasCuposController;
use App\Http\Controllers\Logistica_Recursos_y_Reportes\AulasController;
use App\Http\Controllers\Gestion_Academica\preferenciasDelCursoCUPController;
use App\Http\Controllers\Logistica_Recursos_y_Reportes\DocentesController;
use App\Http\Controllers\Inscripcion_y_Documentacion\EstadoPostulanteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Gestion_Academica\GruposController;
use App\Http\Controllers\Gestion_Academica\EstadosEstudianteController;


// --- RUTAS PÚBLICAS ---
Route::get('/', [autenticacionController::class, 'mostrarLogin'])->name('home');
Route::get('/login', [autenticacionController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [autenticacionController::class, 'iniciarSesion'])->name('login.post');

// Cerrar sesión
Route::post('/logout', [autenticacionController::class, 'cerrarSesion'])->name('logout');

// Recuperación de contraseña
Route::get('/recuperar', [recuperacionController::class, 'mostrarRecuperar'])->name('recuperar.contrasena');
Route::post('/recuperar/codigo', [recuperacionController::class, 'enviarCodigo'])->name('recuperar.enviarCodigo');
Route::post('/recuperar/validar', [recuperacionController::class, 'validarCodigo'])->name('recuperar.validarCodigo');
Route::post('/recuperar/cambiar', [recuperacionController::class, 'cambiarContrasena'])->name('recuperar.cambiar');

// --- RUTAS PROTEGIDAS ---
Route::middleware('auth')->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Usuarios y Roles
    Route::get('/usuarios-roles',                       [usuariosYRolesController::class, 'index'])->name('usuarios.roles');
    Route::post('/usuarios-roles',                      [usuariosYRolesController::class, 'store'])->name('usuarios.store');
    Route::put('/usuarios-roles/{usuario}',             [usuariosYRolesController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios-roles/{usuario}',          [usuariosYRolesController::class, 'destroy'])->name('usuarios.destroy');

    // Inscripción del Postulante
    Route::get('/postulantes',                          [InscripcionController::class, 'index'])->name('postulantes');
    Route::get('/admin/inscripciones',                  [InscripcionController::class, 'index'])->name('admin.inscripciones');

    Route::post('/inscripcion',                         [InscripcionController::class, 'store'])->name('inscripcion.store');
    Route::put('/inscripcion/{inscripcion}',            [InscripcionController::class, 'update'])->name('inscripcion.update');
    Route::delete('/inscripcion/{inscripcion}',         [InscripcionController::class, 'destroy'])->name('inscripcion.destroy');

    // Documentos
    Route::get('/documentos',                           [documentosController::class, 'index'])->name('documentos.index');
    Route::post('/documentos',                          [documentosController::class, 'store'])->name('documentos.store');
    Route::put('/documentos/{documento}',               [documentosController::class, 'update'])->name('documentos.update');
    Route::delete('/documentos/{documento}',            [documentosController::class, 'destroy'])->name('documentos.destroy');

    // Carreras y Cupos
    Route::get('/carreras',                             [CarrerasCuposController::class, 'index'])->name('admin.carreras');
    Route::post('/carreras',                            [CarrerasCuposController::class, 'registrarCarrera'])->name('carreras.store');
    Route::put('/carreras/{carrera}',                   [CarrerasCuposController::class, 'actualizarCupos'])->name('carreras.update');
    Route::delete('/carreras/{carrera}',                [CarrerasCuposController::class, 'deshabilitarCarrera'])->name('carreras.destroy');

    // Aulas
    Route::get('/admin/aulas',                          [AulasController::class, 'index'])->name('admin.aulas');
    Route::post('/admin/aulas',                         [AulasController::class, 'registrar'])->name('admin.aulas.store');
    Route::put('/admin/aulas/{aula}',                   [AulasController::class, 'editar'])->name('admin.aulas.update');
    Route::delete('/admin/aulas/{aula}',                [AulasController::class, 'destroy'])->name('admin.aulas.destroy');

    // Preferencias del Curso CUP
    Route::get('/admin/preferencias-cup',               [preferenciasDelCursoCUPController::class, 'index'])->name('admin.preferencias_cup');
    Route::post('/admin/preferencias-cup',              [preferenciasDelCursoCUPController::class, 'guardarConfig'])->name('admin.preferencias_cup.store');
    Route::put('/admin/preferencias-cup/{id}',          [preferenciasDelCursoCUPController::class, 'editarConfig'])->name('admin.preferencias_cup.update');
    Route::delete('/admin/preferencias-cup/{id}',       [preferenciasDelCursoCUPController::class, 'eliminarConfig'])->name('admin.preferencias_cup.destroy');

    // Docente (Logística)
    Route::get('/admin/docentes',                       [DocentesController::class, 'index'])->name('admin.docentes');
    Route::post('/admin/docentes',                      [DocentesController::class, 'registrar'])->name('admin.docentes.store');
    Route::put('/admin/docentes/{docente}',             [DocentesController::class, 'actualizar'])->name('admin.docentes.update');
    Route::delete('/admin/docentes/{docente}',          [DocentesController::class, 'destroy'])->name('admin.docentes.destroy');

    // Estado del Postulante (Inscripción y Documentación)
    Route::get('/postulante/estado',                    [EstadoPostulanteController::class, 'consultar'])->name('postulante.estado');

    // Gestión de Grupos
    Route::get('/admin/grupos',                         [GruposController::class, 'index'])->name('admin.grupos');
    Route::post('/admin/grupos',                        [GruposController::class, 'store'])->name('admin.grupos.store');
    Route::put('/admin/grupos/{grupo}',                 [GruposController::class, 'update'])->name('admin.grupos.update');
    Route::delete('/admin/grupos/{grupo}',              [GruposController::class, 'destroy'])->name('admin.grupos.destroy');

    // Estado del Postulante (Vista General / Admin)
    Route::get('/admin/estado-postulante',              [EstadosEstudianteController::class, 'index'])->name('admin.estado_postulante');

    // --- HELPER ALIAS ROUTES TO PREVENT SIDEBAR CRASHES ---
    Route::get('/admin/usuarios', function() { return redirect()->route('usuarios.roles'); })->name('admin.usuarios');
    Route::get('/admin/gestionar-documentos', function() { return redirect()->route('documentos.index'); })->name('admin.gestionar.documentos');
    Route::get('/admin/materias', function() { return redirect()->back()->with('success', 'Módulo Materias en desarrollo'); })->name('admin.materias');
    Route::get('/admin/cupos', function() { return redirect()->back()->with('success', 'Módulo Asignación de Cupos en desarrollo'); })->name('admin.cupos');
    Route::get('/admin/documentos-validar', function() { return redirect()->route('documentos.index'); })->name('admin.documentos');
    Route::get('/admin/pagos-validar', function() { return redirect()->back()->with('success', 'Módulo Validar Pagos en desarrollo'); })->name('admin.pagos');
    Route::get('/admin/bitacora', function() { return redirect()->back()->with('success', 'Módulo Bitácora en desarrollo'); })->name('admin.bitacora');
});