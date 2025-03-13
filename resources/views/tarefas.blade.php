@extends('app')


@section('content')
    <div class="container mt-4">
        <button class="return">Voltar</button>
        <h1 class="mb-4">Tarefas</h1>
        
        @if($tarefas->count() > 0)
            <div class="list-group">
                @foreach($tarefas as $tarefa)
                    <div class="list-group-item d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">{{ $tarefa->title }}</h5>
                            <p class="mb-0"><strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($tarefa->start)->format('d/m/Y H:i') }}</p>
                        </div>
                        <span class="badge bg-danger text-white">{{ \Carbon\Carbon::parse($tarefa->start)->diffForHumans() }}</span>
                        <button >Editar Evento</button>
                        <button>ccccd</button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                Não há tarefas para exibir no momento.
            </div>
        @endif
    </div>
@endsection
<script>
   
</script>