<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\User;
use Psy\TabCompletion\Matcher\FunctionsMatcher;
use Illuminate\Auth\Events\Attempting;
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
}