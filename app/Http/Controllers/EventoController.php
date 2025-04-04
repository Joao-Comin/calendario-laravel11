<?php
namespace App\Http\Controllers;

use App\Models\Calendars;
use App\Models\CalendarUser;
use App\Models\Evento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $calendarId = $request->query('calendar_id'); 
        //dump($calendarId);
       
        $query = Evento::query()
        ->select('eventos.id', 'eventos.title', 'eventos.start', 'eventos.end', 'eventos.task', 'eventos.finalizado', 'eventos.description', 'eventos.user_id', 'eventos.calendar_id')
        ->join('calendars', 'eventos.calendar_id', '=', 'calendars.id')
        ->where(function ($q) use ($user) {
            $q->where('calendars.type', 'public') 
            ->orWhere('eventos.user_id', $user->id);
    });
        //filtro
        if ($request->has('task')) {
            $query->where('task', $request->task == '1');
            
        }
       
        if (!empty($calendarId)) {
           //dump('entrou');
            // Se for um único valor, transforma em array
            $ids = is_array($calendarId) ? $calendarId : explode(',', $calendarId);
            //dump($ids);
            $query->whereIn('calendar_id', $ids);
        }else{
            //dump('não entrou');
        }
        
        // Obtém os eventos após os filtros
        $eventos = $query->get();
        
        return response()->json($eventos);
        
    }


    public function store(Request $request)
    {
        $dados = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'id' => 'nullable|integer|exists:eventos,id',
            'action' => 'nullable|string',
            'task' => 'nullable|boolean',
            'finalizado' => 'nullable|boolean',
            'description' => 'nullable|string',
            'calendar_id' => 'required|integer|exists:calendars,id',
            
        ]);
        $dados['user_id'] = Auth::id();
        $dados['task'] = $request->has('task') ? (bool) $request->task : false;
        $dados['finalizado'] = $request->has('finalizado') ? (bool) $request->finalizado : false;

        if (empty($request->id)) {
            Evento::create($dados);
            return redirect()->back()->with('success', 'Evento criado com sucesso!');
        }

        $evento = Evento::findOrFail($request->id);
        $evento->update($dados);

        return redirect()->back()->with('success', 'Evento atualizado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $evento->update($request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'task' => 'nullable|boolean',
            'finalizado' => 'nullable|boolean',
            'description' => 'nullable|string',
            'user_id' => 'nullable|integer',
            'calendar_id' => 'required|integer',
        ]));
        if ($request->has('task')) {
            $evento->task = (bool) $request->task;
            $evento->save();
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $evento = Evento::find($id);

        if (!$evento) {
            return response()->json(['success' => false, 'message' => 'Evento não encontrado'], 404);
        }

        $evento->delete();
        return response()->json(['success' => true, 'message' => 'Evento excluído com sucesso']);
    }

    public function getCalendarios()
{
    $user = Auth::user();
    //dd($user);
    $calendarios = DB::table('calendar_user')
    ->join('calendars', 'calendar_user.calendar_id', '=', 'calendars.id')
    ->where('calendar_user.user_id', $user->id)
    ->select('calendars.id', 'calendars.name', 'calendars.type')
    ->get();
    // dd($calendarios);
    $calendarioPrivado = $calendarios->where('type', 'private')->first();
    // dd($calendarioPrivado);

    return response()->json([
        'calendarios' => $calendarios,
        'default_calendar_id' => $calendarioPrivado ? $calendarioPrivado->id : null,
    ]);
}
}