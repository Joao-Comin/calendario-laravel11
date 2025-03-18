@extends('app')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@vite(['resources/css/style.css'])
@vite(['resources/js/task.js'])
@section('content')
    <div class="container mt-4">
        <button class="return">Voltar</button>
        <button id="btnNovaTask" class="NovaTask">Add Tarefa</button>
        <h1 class="mb-4">Tarefas</h1>
        
        <h2 class="mb-4">A Fazer</h2>
        @if($tarefasNaoConcluidas->count() > 0)
            <div class="list-group">
                @foreach($tarefasNaoConcluidas as $tarefa)
                    <div class="list-group-item d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">{{ $tarefa->title }}</h5>
                            <p class="mb-0"><strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($tarefa->start)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="button-container">

                            <button class="btn-editar" data-tarefa-id="{{ $tarefa->id }}">Editar</button>
                            <button class="btn-concluido" data-tarefa-id="{{ $tarefa->id }}">Concluir</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                não tem tarefas a fazer
            </div>
        @endif

        <h2 class="mb-4">Concluidas</h2>
        @if($tarefasConcluidas->count() > 0)
            <div class="list-group">
                @foreach($tarefasConcluidas as $tarefa)
                    <div class="list-group-item d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-1">{{ $tarefa->title }}</h5>
                            <p class="mb-0"><strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($tarefa->start)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="button-container">
                            <button class="btn-editar" data-tarefa-id="{{ $tarefa->id }}">Editar</button>
                            <button class="btn-desfazer bg-danger" data-tarefa-id="{{ $tarefa->id }}">Desfazer</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning" role="alert">
                Não tem tarefas concluidas
            </div>
        @endif
    </div>
@endsection