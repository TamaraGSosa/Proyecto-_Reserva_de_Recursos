<?php

use Illuminate\Support\Facades\Route;
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
