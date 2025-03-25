<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TarefasController;




Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');


Route::delete('/tarefas/{id}', [TarefasController::class, 'destroy'])->name('tarefas.destroy');

Route::put('/tarefas/{id}', [TarefasController::class, 'concluido'])->name('tarefas.concluido');
Route::put('/tarefas/editar/{id}', [TarefasController::class, 'update'])->name('tarefas.update');
Route::put('/tarefas/desfazer/{id}', [TarefasController::class, 'update2'])->name('tarefas.desfazer');
Route::get('/eventos/usuarios', [EventoController::class, 'getUsers']);
Route::get('/eventos/calendarios', [EventoController::class, 'getCalendarios']);