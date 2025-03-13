<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class TarefasController extends Controller
{
    public function index()
    {
        $tarefas = Evento::where('task', '1')->get();
        // dd($tarefas);
        return view('tarefas',compact('tarefas'));
    }
}