<?php

use App\Mail\ConfirmacionReserva;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\Reserva;



Route::get('/test-mail', function () {
    $data = [
        'nombre' => 'Juan',
        'producto' => 'Aula 101',
        'fecha' => '2025-03-10',
        'email' => 'test@example.com'
    ];

    Mail::to('test@example.com')->send(new ConfirmacionReserva($data));

    return 'Correo enviado!';
});

// Redirigir la raíz al login
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// Redirigir /home al login
Route::get('/home', function () {
    return redirect('/login');
});

Route::get('reserva', function () {
    Mail::to('cesarojas@gmail.com')
        ->send(new ConfirmacionReserva([]));
    return "mensaje enviado";
})->name('reserva');