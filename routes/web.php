<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\UserController;

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Redirigir /home al login
Route::get('/home', function () {
    return redirect('/login');
});

Route::resource('usuarios', UserController::class)->middleware('auth');
Route::get('/personas/{dni}', [App\Http\Controllers\PersonController::class, 'search']);


// Rutas de reportes
Route::get('/reportes', function () {
    return view('reportes.index');
})->name('reportes.index')->middleware('auth');
// cambio de contraseña
Route::middleware('auth')->group(function () {
    Route::get('/perfil/cambiar-password', [App\Http\Controllers\ProfileController::class, 'editPassword'])->name('profile.password.edit');
    Route::post('/perfil/cambiar-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password.update');
});


// Route for daily resource PDF report
Route::get('/reporte/recursos/pdf/dia', [PdfController::class, 'exportarRecursosPorDia'])->name('reporte.recursos.pdf.dia');


Route::get('/reporte/recursos/pdf', [PdfController::class, 'exportarRecursos'])->name('reporte.recursos.pdf')->middleware('auth');


