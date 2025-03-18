<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class TarefasController extends Controller
{
    public function index()
    {
        $tarefasNaoConcluidas = Evento::where('task', '1')->where('finalizado', '0')->get();
        $tarefasConcluidas = Evento::where('task', '1')->where('finalizado', '1')->get();
        return view('tarefas', compact('tarefasNaoConcluidas', 'tarefasConcluidas'));
    }

    public function destroy($id)
    {
        $tarefa = Evento::find($id);

        if (!$tarefa) {
            return response()->json(['success' => false, 'message' => 'Tarefa não encontrada'], 404);
        }

        $tarefa->delete();
        return response()->json(['success' => true, 'message' => 'Tarefa concluída com sucesso']);
    }
    public function show($id)
    {
        $evento = Evento::find($id);
        if (!$evento) {
            return response()->json(['success' => false, 'message' => 'Tarefa não encontrada'], 404);
        }
        return response()->json($evento);
    }

    public function concluido($id)
    {
        $tarefa = Evento::find($id);

        if (!$tarefa) {
            return response()->json(['success' => false, 'message' => 'Tarefa não encontrada'], 404);
        }

        $tarefa->finalizado = 1;
        $tarefa->save();

        return response()->json(['success' => true, 'message' => 'Tarefa concluída com sucesso']);
    }
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $evento->update($request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'task' => 'nullable|boolean',
            'finalizado' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]));
        if ($request->has('task')) {
            $evento->task = (bool) $request->task;
            $evento->save();
        }

        return response()->json(['success' => true]);
    }
    public function update2($id)
    {
        $tarefa = Evento::find($id);

        if (!$tarefa) {
            return response()->json(['success' => false, 'message' => 'Tarefa não encontrada'], 404);
        }

        $tarefa->finalizado = 0;
        $tarefa->save();

        return response()->json(['success' => true, 'message' => 'Tarefa desmarcada como concluída']);
    }
}