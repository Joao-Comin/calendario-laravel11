<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TarefasController;
use App\Http\Controllers\LoginController;

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/', [LoginController::class, 'loginProcess'])->name('login.process');

Route::get('/calendar', function () {
    return view('fullcalendar');
})->name('calendar');


Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

Route::get('/tarefas', [TarefasController::class, 'index']);
Route::delete('/tarefas/{id}', [TarefasController::class, 'destroy'])->name('tarefas.destroy');
Route::get('/tarefas/editar/{id}', [TarefasController::class, 'show'])->name('tarefas.show');
Route::put('/tarefas/{id}', [TarefasController::class, 'concluido'])->name('tarefas.concluido');
Route::put('/tarefas/editar/{id}', [TarefasController::class, 'update'])->name('tarefas.update');
Route::put('/tarefas/desfazer/{id}', [TarefasController::class, 'update2'])->name('tarefas.desfazer');
Route::get('/eventos/usuarios', [EventoController::class, 'getUsers']);
