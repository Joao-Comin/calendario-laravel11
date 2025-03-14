<?php
namespace App\Http\Controllers;

use App\Models\Evento;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::select('id', 'title', 'start', 'end', 'color')->get();
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
            'action' => 'nullable|string'
        ]);

        

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
            'end' => 'required|date|after_or_equal:start',
            'color' => 'nullable|string|max:7',
        ]));

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

}
