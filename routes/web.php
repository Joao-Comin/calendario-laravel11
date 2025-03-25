<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TarefasController;
use App\Http\Controllers\LoginController;
Route::get('/calendar', function () {
    return view('fullcalendar');
})->name('calendar');
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'loginProcess'])->name('login.process');
Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
Route::get('/tarefas', [TarefasController::class, 'index']);






