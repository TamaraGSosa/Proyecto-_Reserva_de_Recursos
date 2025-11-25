<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Models\Person;

// Rutas de Reportes
Route::get('/reporte/reservas/pdf', [ReporteController::class, 'generarReporteReservas'])->name('reportes.reservas.pdf');
Route::get('/reportes/reservas', function () {
    return view('reportes.reservas');
})->name('reportes.reservas.index');

// Rutas accesibles para todos los usuarios autenticados
// Dashboard
Route::get('/', [ReservationController::class, 'dashboard'])->name('panel.dashboard');

// JSON y cambios de estado
Route::get('/reservations/{reservation}/json', [ReservationController::class, 'json'])->name('reservations.json');
Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'changeStatus'])->name('reservations.change-status');
Route::post('/reservations/update-status', [ReservationController::class, 'actualizarEstados'])->name('reservations.update');

// Personas
Route::get('/persons/search/{dni}', [PersonController::class, 'search'])->name('persons.search');

// Recursos
Route::get('/resources/available', [ResourceController::class, 'available'])->name('resources.available');
Route::get('/resources/{resource}/json', [ResourceController::class, 'json'])->name('resources.json');
Route::resource('resources', ResourceController::class);

// Reservaciones (solo una línea)
Route::resource('reservations', ReservationController::class);
