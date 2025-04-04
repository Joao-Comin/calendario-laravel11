<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Calendars;
use App\Models\CalendarUser;
use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Container\Attributes\DB;
use Illuminate\Support\Facades\Auth;



class LoginController extends Controller{
    
    public function index(){
        
        return view('login.index');
    }

    public function loginProcess(LoginRequest $request){
        
        $request->validated();

        $autenticado = Auth::attempt(['email' => $request->email, 'password' => $request->password]);      
        
        if(!$autenticado){
            return back()->withInput()->with('error', 'Email ou Senha Invalidos');
        }
               
        $user = Auth::user();
        $user = User::find($user->id);

        return redirect()->route('calendar');
    }
    public function create()
    {

        // Carregar a VIEW
        return view('login.create');
    }
    public function store(Request $request)
{
    // Criar o usuário
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Criar um calendário privado para o usuário recém-criado e armazenar na variável
    $calendar = Calendars::create([
        'name' => $user->name,
        'type' => 'private',
        'color' => '#0000FF'
    ]);

    // Criar relação na tabela intermediária
    CalendarUser::create([
        'user_id' => $user->id,
        'calendar_id' => $calendar->id
    ]);

    return redirect()->route('login')->with('success', 'Usuário cadastrado com sucesso!');
}
}