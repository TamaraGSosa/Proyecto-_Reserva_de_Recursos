<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
use App\Models\Person;

// Rutas accesibles para todos los usuarios autenticados
Route::get('/', [ReservationController::class, 'dashboard'])->name('panel.dashboard');
Route::get('/reservations/{reservation}/json', [ReservationController::class, 'json'])->name('reservations.json');
Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'changeStatus'])->name('reservations.change-status');


// Rutas para administradores
Route::get('/resources/{resource}/json', [ResourceController::class, 'json'])->name('resources.json');
Route::post('/reservations/update-status', [ReservationController::class, 'actualizarEstados'])->name('reservations.update');
Route::get('/persons/search/{dni}', [PersonController::class, 'search'])->name('persons.search');

// Rutas para usuarios autenticados
Route::get('/resources/available', [ResourceController::class, 'available'])->name('resources.available');
Route::resource('resources', ResourceController::class);
Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
Route::get('/reservations/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
Route::put('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
