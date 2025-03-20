<?php
namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EventoController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    
    $query = Evento::where('user_id', $user->id)
        ->select('id', 'title', 'start', 'end', 'color', 'task', 'finalizado', 'description', 'user_id');
    
    
    if ($request->has('task') && $request->task == '1') {
        $query->where('task', true); 
    }if ($request->has('task') && $request->task == '0') {
        $query->where('task', false);
    }
    

    $eventos = $query->get();
    return response()->json($eventos);
    
   
}

    public function store(Request $request)
    {
        $dados = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'color' => 'required|string|max:7',
            'id' => 'nullable|integer|exists:eventos,id',
            'action' => 'nullable|string',
            'task' => 'nullable|boolean',
            'finalizado' => 'nullable|boolean',
            'description' => 'nullable|string',
            'user_id' => 'integer|nullable',
        ]);
        $dados['task'] = $request->has('task') ? (bool) $request->task : false;
        $dados['finalizado'] = $request->has('finalizado') ? (bool) $request->finalizado : false;

        if (empty($dados['id'])) {
            Evento::create($dados);
            return redirect()->back()->with('success', 'Evento criado com sucesso!');
        }

        $evento = Evento::findOrFail($dados['id']);
        $evento->update($dados);

        return redirect()->back()->with('success', 'Evento atualizado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $evento->update($request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'date|after_or_equal:start',
            'color' => 'nullable|string|max:7',
            'task' => 'nullable|boolean',
            'finalizado' => 'nullable|boolean',
            'description' => 'nullable|string',
            'user_id' => 'nullable|integer',
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
    public function getUsers(){
        
            $users = User::orderBy('name', 'asc')->get(['id', 'name']);
            return response()->json($users);
    }     
}