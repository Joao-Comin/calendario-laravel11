<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TarefasController;

Route::get('/', function () {
    return view('fullcalendar');
});

Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

Route::get('/tarefas', [TarefasController::class, 'index']);
