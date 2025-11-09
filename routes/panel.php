<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;

Route::get('/', function () {
    return view('panel.index');
});
Route::get('/resources/{resource}/json', [ResourceController::class, 'json'])->name('resources.json');

Route::resource('resources', ResourceController::class);
