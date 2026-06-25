<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AdminController;

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
// PANEL DEL USUARIO
// ============================
Route::middleware(['auth'])->group(function () {
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
});
