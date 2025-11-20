<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
use App\Http\Controllers\PdfController;


Route::get('/reporte/pdf/rango', [PdfController::class, 'exportarPorRangoDeFechas'])->name('reporte.pdf.rango');
Route::get('/reporte/form-rango-fechas', function () {
    return view('reportes.form_rango_fechas');
})->name('reporte.form_rango_fechas');
Route::get('/reporte/form-dia', function () {
    return view('reportes.form_dia');
})->name('reporte.form_dia');
Route::get('/reporte/pdf/recursos/dia', [PdfController::class, 'exportarRecursosPorDia'])->name('reporte.recursos.pdf.dia');
Route::get('/reporte/form-recursos', function () {
    return view('reportes.form_recursos');
})->name('reporte.form_recursos');
Route::get('/reporte/pdf/recursos', [PdfController::class, 'exportarRecursos'])->name('reporte.recursos.generar');
=======
use App\Http\Controllers\UserController;
>>>>>>> 7d7086961ccb0d6cc3a0019247abee02ec7b9523

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
