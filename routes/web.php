<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReservaPublicaController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Ruta temporal para probar formulario
Route::get('/test-form', function () {
    return view('test_form');
})->name('test-form');

// Rutas de partidos
Route::get('/buscar-partidos', [App\Http\Controllers\PartidoController::class, 'index'])->name('buscar-partidos');
Route::get('/partidos/create', [App\Http\Controllers\PartidoController::class, 'create'])->middleware(['auth', 'account.active', 'role:jugador,dueno_cancha,admin_plataforma'])->name('partidos.create');
Route::get('/partidos/{partido}', [App\Http\Controllers\PartidoController::class, 'show'])->name('partidos.show');
Route::get('/reservar-canchas', [ReservaPublicaController::class, 'index'])->name('reservar-canchas');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Rutas protegidas (requieren autenticación)
Route::middleware(['auth', 'account.active'])->group(function () {
    Route::get('/mis-partidos', [App\Http\Controllers\MisPartidosController::class, 'index'])->name('mis-partidos');
    Route::post('/mis-partidos/{partido}/cancelar-participacion', [App\Http\Controllers\MisPartidosController::class, 'cancelarParticipacion'])->name('mis-partidos.cancelar-participacion');
    Route::post('/mis-partidos/{partido}/cancelar-partido', [App\Http\Controllers\MisPartidosController::class, 'cancelarPartido'])->name('mis-partidos.cancelar-partido');
    Route::get('/mis-reservas', [ReservaPublicaController::class, 'mine'])->name('mis-reservas');
    
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');

    Route::middleware('role:jugador,dueno_cancha,admin_plataforma')->group(function () {
        Route::post('/partidos', [App\Http\Controllers\PartidoController::class, 'store'])->name('partidos.store');
        Route::post('/partidos/{partido}/join', [App\Http\Controllers\PartidoController::class, 'join'])->name('partidos.join');
        Route::post('/partidos/{partido}/leave', [App\Http\Controllers\PartidoController::class, 'leave'])->name('partidos.leave');
        Route::delete('/partidos/{partido}', [App\Http\Controllers\PartidoController::class, 'destroy'])->name('partidos.destroy');
        Route::post('/reservas', [ReservaPublicaController::class, 'store'])->name('reservas.store');
    });
    
    // Rutas del dashboard de administración
    Route::prefix('admin')->name('admin.')
        ->middleware('role:dueno_cancha,admin_plataforma')
        ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
    // Rutas de canchas
    Route::resource('canchas', App\Http\Controllers\Admin\CanchaController::class);
    Route::post('/canchas/{cancha}/toggle-estado', [App\Http\Controllers\Admin\CanchaController::class, 'toggleEstado'])->name('canchas.toggle-estado');
    Route::get('/canchas/{cancha}/details', [App\Http\Controllers\Admin\CanchaController::class, 'getDetails'])->name('canchas.details');
    
    // Rutas de reservas
    Route::resource('reservas', App\Http\Controllers\Admin\ReservaController::class);
    Route::post('/reservas/{reserva}/cambiar-estado', [App\Http\Controllers\Admin\ReservaController::class, 'cambiarEstado'])->name('reservas.cambiar-estado');
    Route::get('/reservas-por-fecha', [App\Http\Controllers\Admin\ReservaController::class, 'porFecha'])->name('reservas.por-fecha');
    
    // Rutas de turnos
    Route::resource('turnos', App\Http\Controllers\Admin\TurnoController::class);
    Route::post('/turnos/{turno}/toggle-estado', [App\Http\Controllers\Admin\TurnoController::class, 'toggleEstado'])->name('turnos.toggle-estado');
    Route::get('/turnos-por-cancha', [App\Http\Controllers\Admin\TurnoController::class, 'porCancha'])->name('turnos.por-cancha');

        Route::prefix('moderacion')->name('moderacion.')
            ->middleware('role:admin_plataforma')
            ->group(function () {
                Route::get('/usuarios', [App\Http\Controllers\Admin\ModerationController::class, 'users'])->name('usuarios.index');
                Route::put('/usuarios/{user}/rol', [App\Http\Controllers\Admin\ModerationController::class, 'updateRole'])->name('usuarios.role');
                Route::post('/usuarios/{user}/suspension', [App\Http\Controllers\Admin\ModerationController::class, 'toggleSuspension'])->name('usuarios.suspension');
                Route::get('/canchas', [App\Http\Controllers\Admin\ModerationController::class, 'courts'])->name('canchas.index');
                Route::put('/canchas/{cancha}', [App\Http\Controllers\Admin\ModerationController::class, 'updateCourt'])->name('canchas.update');
                Route::get('/partidos', [App\Http\Controllers\Admin\ModerationController::class, 'matches'])->name('partidos.index');
                Route::put('/partidos/{partido}', [App\Http\Controllers\Admin\ModerationController::class, 'updateMatch'])->name('partidos.update');
            });
    });
});
