<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Evento;
use App\Models\Calendars;
use App\Models\CalendarUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class EventosComponent extends Component
{
    public $showModal = false;
    public $calendarName = '';
    public $type = 'public';
    public $color = '#0000FF';
    public $calendarId;

    protected $listeners = ['deletarEvento' => 'deletarEvento', 'pegarCalendarios' => 'pegarCalendarios', 
    'criarCalendario'=> 'criarCalendario', 'pegarCalendariosUsuario'=> 'pegarCalendariosUsuario', 'moverEvento' => 'moverEvento'];

    public function deletarEvento($eventId)
{
    
    info("here");
    $evento = Evento::find($eventId);
    info($evento);
    if ($evento) {
        
        $evento->delete();

        $this->dispatch('EventoDeletado', ['id' => $eventId, 'message' => 'Evento deletado com sucesso!']);
    } else {
        $this->dispatch('erroEvento', ['message' => 'Evento não encontrado!']);
    }
}
public function pegarCalendarios()
{
    $user = Auth::user();
    $calendarios = DB::table('calendar_user')
        ->join('calendars', 'calendar_user.calendar_id', '=', 'calendars.id')
        ->where(function ($query) use ($user) {
            $query->where('calendars.type', 'public')
                  ->orWhere('calendar_user.user_id', $user->id);
        })
        ->select('calendars.id', 'calendars.name', 'calendars.type', 'calendars.color')
        ->get();
    // dd($calendarios);
    $calendariosArray = json_decode(json_encode($calendarios), true);

    $calendarioPrivado = collect($calendariosArray)->firstWhere('type', 'private');

    $dados = [
        'calendarios' => $calendariosArray,
        'default_calendar' => $calendarioPrivado ?: null,
    ];

    info('🔍 Dados enviados pelo Livewire:', $dados);

    $this->dispatch('CalendariosCarregados', $dados);
}
public function criarCalendario($data)
{
    //dd($data);
    if ($data) {
       
        foreach($data as $calendario){
            //dd($calendario);
            $this->calendarName = $calendario['name'];
            $this->type = $calendario['type'];
            $this->color = $calendario['color'];
        }
    }
   
    $this->validate([
        'calendarName' => 'required|max:255',
        'type' => 'required|in:public,private',
        'color' => 'required|max:7'
    ]);
    $user = Auth::user();
    $calendar = Calendars::create([
        'name' => $this->calendarName,
        'type' => $this->type,
        'color'=> $this->color,
    ]);

    $calendar->users()->attach($user->id);
   
    $this->pegarCalendariosUsuario($user->id);

    
    
    $this->dispatch('calendarioCriado');
    info('📢 Disparando evento CalendarioCriado!');
}

public function pegarCalendariosUsuario()
{
    $user = Auth::user();

    $calendarios = DB::table('calendars')
        ->leftJoin('calendar_user', function ($join) use ($user) {
            $join->on('calendar_user.calendar_id', '=', 'calendars.id')
                ->where('calendar_user.user_id', '=', $user->id);
        })
        ->where(function ($query) use ($user) {
            $query->where('calendars.type', 'public')
                  ->orWhere('calendar_user.user_id', $user->id);
        })
        ->select('calendars.id', 'calendars.name', 'calendars.type','calendars.color')
        ->distinct()
        ->get();

    $this->dispatch('CalendariosUsuarioCarregados', $calendarios->toArray());
}


public function moverEvento($data)
{
    
    $evento = Evento::findOrFail($data['id']);

    $evento->update([
        'title' => $data['title'],
        'start' => $data['start'],
        'end' => $data['end'] , null,
        'task' => $data['task'] , false,
        'finalizado' => $data['finalizado'] , false,
        'description' => $data['description'] , null,
        'calendar_id' => $data['calendar_id'],
    ]);

    $this->dispatch('eventoAtualizado', ['message' => 'Evento movido com sucesso!']);
}


public function mount()
{
    $this->pegarCalendariosUsuario(); 
}

}

