<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\InscripcionController;
use Illuminate\Support\Facades\Auth;

// --- PUBLIC & AUTH ROUTES ---
Route::get('/', [AuthController::class, 'showLogin'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Recovery
Route::get('/forgot-password', [AuthController::class, 'showForgot'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendRecoveryCode'])->name('password.email');
Route::get('/verify-password', [AuthController::class, 'showVerify'])->name('password.verify');
Route::post('/verify-password', [AuthController::class, 'verifyCode'])->name('password.update');

// --- PROTECTED ROUTES ---
Route::middleware('auth')->group(function () {

    // General Dashboard Router based on User Role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->rol && $user->rol->nombre_rol === 'Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDocente()) {
            return redirect()->route('docente.dashboard');
        } elseif ($user->isPostulante()) {
            return redirect()->route('postulante.dashboard');
        }
        return redirect()->route('login');
    })->name('dashboard');

    // --- SUPERADMINISTRADOR ROUTES ---
    Route::get('/superadmin/dashboard', [\App\Http\Controllers\SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

    // --- ADMINISTRATOR ROUTES ---
    Route::middleware(\App\Http\Middleware\AuditMiddleware::class)->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // CRUD Usuarios
        Route::get('/admin/usuarios', [AdminController::class, 'indexUsuarios'])->name('admin.usuarios');
        Route::post('/admin/usuarios', [AdminController::class, 'storeUsuario'])->name('admin.usuarios.store');
        Route::put('/admin/usuarios/{id}', [AdminController::class, 'updateUsuario'])->name('admin.usuarios.update');
        Route::delete('/admin/usuarios/{id}', [AdminController::class, 'destroyUsuario'])->name('admin.usuarios.destroy');
        
        // CRUD Carreras
        Route::get('/admin/carreras', [AdminController::class, 'indexCarreras'])->name('admin.carreras');
        Route::post('/admin/carreras', [AdminController::class, 'storeCarrera'])->name('admin.carreras.store');
        Route::put('/admin/carreras/{id}', [AdminController::class, 'updateCarrera'])->name('admin.carreras.update');
        Route::delete('/admin/carreras/{id}', [AdminController::class, 'destroyCarrera'])->name('admin.carreras.destroy');
        
        // CRUD Aulas
        Route::get('/admin/aulas', [AdminController::class, 'indexAulas'])->name('admin.aulas');
        Route::post('/admin/aulas', [AdminController::class, 'storeAula'])->name('admin.aulas.store');
        Route::delete('/admin/aulas/{id}', [AdminController::class, 'destroyAula'])->name('admin.aulas.destroy');
        
        // CRUD Materias
        Route::get('/admin/materias', [AdminController::class, 'indexMaterias'])->name('admin.materias');
        Route::post('/admin/materias', [AdminController::class, 'storeMateria'])->name('admin.materias.store');
        Route::delete('/admin/materias/{id}', [AdminController::class, 'destroyMateria'])->name('admin.materias.destroy');
        
        // CRUD Grupos
        Route::get('/admin/grupos', [AdminController::class, 'indexGrupos'])->name('admin.grupos');
        Route::post('/admin/grupos', [AdminController::class, 'storeGrupo'])->name('admin.grupos.store');
        
        // Document Validation (existing - for postulantes' uploaded documents)
        Route::get('/admin/documentos', [AdminController::class, 'indexDocumentos'])->name('admin.documentos');
        Route::post('/admin/documentos/{id}/validar', [AdminController::class, 'validateDocument'])->name('admin.documentos.validate');

        // Gestionar Documentos - Requisitos (CRUD de tipos/requisitos de documentos)
        Route::get('/admin/gestionar-documentos', [AdminController::class, 'indexGestionDocumentos'])->name('admin.gestionar.documentos');
        Route::post('/admin/gestionar-documentos', [AdminController::class, 'storeGestionDocumento'])->name('admin.gestionar.documentos.store');
        Route::put('/admin/gestionar-documentos/{id}', [AdminController::class, 'updateGestionDocumento'])->name('admin.gestionar.documentos.update');
        Route::delete('/admin/gestionar-documentos/{id}', [AdminController::class, 'destroyGestionDocumento'])->name('admin.gestionar.documentos.destroy');
        
        // Payment Approval
        Route::get('/admin/pagos', [AdminController::class, 'indexPagos'])->name('admin.pagos');
        Route::post('/admin/pagos/{id}/aprobar', [AdminController::class, 'approvePayment'])->name('admin.pagos.approve');
        
        // Stored Seat Assignment (Meritocratic process)
        Route::get('/admin/cupos', [AdminController::class, 'indexCupos'])->name('admin.cupos');
        Route::post('/admin/cupos', [AdminController::class, 'storeCupo'])->name('admin.cupos.store');
        Route::post('/admin/cupos/asignar', [AdminController::class, 'runSeatAssignment'])->name('admin.cupos.assign');
        
        // CRUD Inscripciones (CU03 & CU04)
        Route::get('/admin/inscripciones', [InscripcionController::class, 'index'])->name('admin.inscripciones');
        Route::post('/admin/inscripciones', [InscripcionController::class, 'store'])->name('admin.inscripciones.store');
        Route::get('/admin/inscripciones/{id}', [InscripcionController::class, 'detail'])->name('admin.inscripciones.detail');
        Route::put('/admin/inscripciones/{id}', [InscripcionController::class, 'update'])->name('admin.inscripciones.update');
        Route::delete('/admin/inscripciones/{id}', [InscripcionController::class, 'destroy'])->name('admin.inscripciones.destroy');
        Route::post('/admin/inscripciones/{id}/validar', [InscripcionController::class, 'validateData'])->name('admin.inscripciones.validate');
        Route::post('/admin/inscripciones/{id}/pago', [InscripcionController::class, 'generatePayment'])->name('admin.inscripciones.payment');
        Route::post('/admin/inscripciones/documento/{id}', [InscripcionController::class, 'validateDocumentDetails'])->name('admin.inscripciones.documento.validate');

        // System Audit Review
        Route::get('/admin/bitacora', [AdminController::class, 'viewBitacora'])->name('admin.bitacora');
    });

    // --- DOCENTE ROUTES ---
    Route::get('/docente/dashboard', [DocenteController::class, 'dashboard'])->name('docente.dashboard');
    Route::get('/docente/grupo/{id}', [DocenteController::class, 'viewGrupo'])->name('docente.grupo.view');
    Route::post('/docente/grupo/{grupoId}/evaluacion', [DocenteController::class, 'storeEvaluacion'])->name('docente.evaluacion.store');
    Route::delete('/docente/evaluacion/{id}', [DocenteController::class, 'destroyEvaluacion'])->name('docente.evaluacion.destroy');
    
    // Notes grading sheets
    Route::get('/docente/grupo/{grupoId}/evaluacion/{evaluacionId}/notas', [DocenteController::class, 'viewNotas'])->name('docente.notas.view');
    Route::post('/docente/grupo/{grupoId}/evaluacion/{evaluacionId}/notas', [DocenteController::class, 'storeNotas'])->name('docente.notas.store');
    
    // Attendance sheets
    Route::get('/docente/grupo/{grupoId}/asistencia', [DocenteController::class, 'viewAsistencia'])->name('docente.asistencia.view');
    Route::post('/docente/grupo/{grupoId}/asistencia', [DocenteController::class, 'storeAsistencia'])->name('docente.asistencia.store');

    // --- POSTULANTE ROUTES ---
    Route::get('/postulante/dashboard', [PostulanteController::class, 'dashboard'])->name('postulante.dashboard');
    Route::get('/postulante/progreso', [InscripcionController::class, 'viewProgress'])->name('postulante.progreso');
    Route::post('/postulante/persona', [PostulanteController::class, 'updatePersona'])->name('postulante.persona.update');
    Route::post('/postulante/documento', [PostulanteController::class, 'uploadDocument'])->name('postulante.documento.upload');
    Route::post('/postulante/pago', [PostulanteController::class, 'registerPayment'])->name('postulante.pago.register');
    Route::post('/postulante/carrera', [PostulanteController::class, 'changeCareer'])->name('postulante.carrera.change');

});
