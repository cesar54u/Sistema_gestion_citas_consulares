<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CorreoController;
use App\Http\Controllers\Admin\ExportController;

// ============================
// PÁGINA DE INICIO PÚBLICA
// ============================
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// ============================
// AUTENTICACIÓN
// ============================
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================
// VERIFICACIÓN DE CORREO
// ============================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard')->with('success', '¡Tu correo electrónico ha sido verificado con éxito!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '¡Enlace de verificación enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ============================
// PANEL DEL USUARIO
// ============================
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard',  [CitaController::class, 'index'])->name('dashboard');

    // Citas
    Route::get('/citas/crear',            [CitaController::class, 'create'])->name('citas.create');
    Route::post('/citas',                 [CitaController::class, 'store'])->name('citas.store');
    Route::get('/citas/mis-citas',        [CitaController::class, 'misCitas'])->name('citas.mis-citas');
    Route::post('/citas/{cita}/cancelar', [CitaController::class, 'cancel'])->name('citas.cancel');

    // AJAX: horarios disponibles
    Route::get('/citas/horarios-disponibles', [CitaController::class, 'horariosDisponibles'])->name('citas.horarios');

    // Historial del usuario
    Route::get('/historial', [CitaController::class, 'historial'])->name('citas.historial');

    // Perfil
    Route::get('/perfil',  [AuthController::class, 'showPerfil'])->name('perfil');
    Route::put('/perfil',  [AuthController::class, 'updatePerfil'])->name('perfil.update');
});

// ============================
// PANEL ADMINISTRATIVO
// ============================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Gestión de Usuarios
    Route::get('/usuarios',              [AdminController::class, 'usuarios'])->name('usuarios');
    Route::get('/usuarios/crear',        [AdminController::class, 'crearUsuario'])->name('usuarios.crear');
    Route::post('/usuarios',             [AdminController::class, 'guardarUsuario'])->name('usuarios.guardar');
    Route::get('/usuarios/{usuario}/editar',    [AdminController::class, 'editarUsuario'])->name('usuarios.editar');
    Route::put('/usuarios/{usuario}',           [AdminController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
    Route::delete('/usuarios/{usuario}',        [AdminController::class, 'eliminarUsuario'])->name('usuarios.eliminar');

    // Gestión de Servicios
    Route::get('/servicios',             [AdminController::class, 'servicios'])->name('servicios');
    Route::get('/servicios/crear',       [AdminController::class, 'crearServicio'])->name('servicios.crear');
    Route::post('/servicios',            [AdminController::class, 'guardarServicio'])->name('servicios.guardar');
    Route::get('/servicios/{servicio}/editar',  [AdminController::class, 'editarServicio'])->name('servicios.editar');
    Route::put('/servicios/{servicio}',         [AdminController::class, 'actualizarServicio'])->name('servicios.actualizar');
    Route::delete('/servicios/{servicio}',      [AdminController::class, 'eliminarServicio'])->name('servicios.eliminar');

    // Gestión de Disponibilidad
    Route::get('/disponibilidad',        [AdminController::class, 'disponibilidad'])->name('disponibilidad');
    Route::post('/disponibilidad',       [AdminController::class, 'guardarDisponibilidad'])->name('disponibilidad.guardar');
    Route::delete('/disponibilidad/{disponibilidad}', [AdminController::class, 'eliminarDisponibilidad'])->name('disponibilidad.eliminar');

    // Gestión de Citas
    Route::get('/citas',                         [AdminController::class, 'citas'])->name('citas');
    Route::get('/citas/{cita}',                  [AdminController::class, 'verCita'])->name('citas.ver');
    Route::post('/citas/{cita}/aprobar',         [AdminController::class, 'aprobarCita'])->name('citas.aprobar');
    Route::post('/citas/{cita}/rechazar',        [AdminController::class, 'rechazarCita'])->name('citas.rechazar');
    Route::post('/citas/{cita}/completar',       [AdminController::class, 'completarCita'])->name('citas.completar');
    Route::post('/citas/{cita}/reprogramar',     [AdminController::class, 'reprogramarCita'])->name('citas.reprogramar');

    // Historial
    Route::get('/historial', [AdminController::class, 'historial'])->name('historial');

    // Gestión de Correos
    Route::get('/correos',                            [CorreoController::class, 'index'])->name('correos');
    Route::get('/correos/configuracion',              [CorreoController::class, 'configuracion'])->name('correos.configuracion');
    Route::post('/correos/configuracion',             [CorreoController::class, 'guardarConfiguracion'])->name('correos.configuracion.guardar');
    Route::post('/correos/recordatorios-masivos',     [CorreoController::class, 'enviarRecordatoriosMasivos'])->name('correos.recordatorios');
    Route::post('/correos/cita/{cita}/estado',        [CorreoController::class, 'enviarEstadoCita'])->name('correos.estado-cita');
    Route::delete('/correos/{correo}',                [CorreoController::class, 'eliminar'])->name('correos.eliminar');

    // Exportación de datos
    Route::get('/export/historial/pdf',   [ExportController::class, 'historialPdf'])->name('export.historial.pdf');
    Route::get('/export/historial/excel', [ExportController::class, 'historialExcel'])->name('export.historial.excel');
    Route::get('/export/citas/pdf',       [ExportController::class, 'citasPdf'])->name('export.citas.pdf');
    Route::get('/export/citas/excel',     [ExportController::class, 'citasExcel'])->name('export.citas.excel');
});
