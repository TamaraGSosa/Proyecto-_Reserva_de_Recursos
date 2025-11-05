<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResourceController;
Route::get('/',function(){
    return view('panel.index');
});
Route::resource('resources', ResourceController::class);