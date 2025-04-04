<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\TarefasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CalendarController;
use App\Livewire\EventosComponent;

Route::get('/calendar-livewire', EventosComponent::class)->name('calendar.livewire');
Route::get('/calendar', function () {
    return view('fullcalendar');
})->name('calendar');
Route::get('/', [LoginController::class, 'index'])->name('login');
Route::get('/create-user-login', [LoginController::class, 'create'])->name('login.create-user');
Route::post('/', [LoginController::class, 'loginProcess'])->name('login.process');
Route::post('/store-user-login', [LoginController::class, 'store'])->name('login.store-user');

Route::get('/eventos', [EventoController::class, 'index']);
Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
Route::get('/tarefas', [TarefasController::class, 'index']);
Route::post('/calendario', [CalendarController::class, 'store']);
Route::get('/eventos/calendarios', [EventoController::class, 'getCalendarios']);








