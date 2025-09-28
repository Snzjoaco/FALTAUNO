<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Ruta temporal para probar formulario
Route::get('/test-form', function () {
    return view('test_form');
})->name('test-form');

// Rutas de partidos
Route::get('/buscar-partidos', [App\Http\Controllers\PartidoController::class, 'index'])->name('buscar-partidos');
Route::get('/partidos/create', [App\Http\Controllers\PartidoController::class, 'create'])->name('partidos.create');
Route::post('/partidos', [App\Http\Controllers\PartidoController::class, 'store'])->name('partidos.store');
Route::get('/partidos/{partido}', [App\Http\Controllers\PartidoController::class, 'show'])->name('partidos.show');
Route::post('/partidos/{partido}/join', [App\Http\Controllers\PartidoController::class, 'join'])->name('partidos.join');
Route::post('/partidos/{partido}/leave', [App\Http\Controllers\PartidoController::class, 'leave'])->name('partidos.leave');
Route::delete('/partidos/{partido}', [App\Http\Controllers\PartidoController::class, 'destroy'])->name('partidos.destroy');

Route::get('/reservar-canchas', function () {
    return view('reservar-canchas');
})->name('reservar-canchas');

// Rutas de autenticación
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/mis-partidos', function () {
        return view('mis-partidos');
    })->name('mis-partidos');
    
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');
    
    Route::get('/mis-reservas', function () {
        return view('mis-reservas');
    })->name('mis-reservas');
    
    // Rutas del dashboard de administración
    Route::prefix('admin')->name('admin.')->group(function () {
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
    });
});
