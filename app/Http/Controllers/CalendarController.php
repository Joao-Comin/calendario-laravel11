<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendars;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CalendarController extends Controller
{
    public function index(){
       
    }
    public function store(Request $request)
{
    $user = Auth::user();

    $dados = $request->validate([
        'name' => 'string|required',
        'type' => 'required|in:public,private,group,geral',
        'color' => 'required'
    ]);

    $calendar = Calendars::create($dados);

    $calendar->users()->attach($user->id);

    return response()->json([
        'message' => 'Calendário criado com sucesso!',
        'calendar' => $calendar
    ], 201);
}
public function showUserCalendars($userId) {
    try {
        $user = User::findOrFail($userId);

        $calendars = $user->calendars;
        
        return response()->json($calendars);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Usuário não encontrado.'], 404);
    }
}
    
    
}
