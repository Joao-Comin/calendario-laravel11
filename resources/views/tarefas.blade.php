@extends('app')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@vite(['resources/css/style.css'])
@vite(['resources/js/task.js'])

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar à Esquerda -->
    <aside class="w-64 bg-white shadow-lg p-6 flex-shrink-0">
        <h2 class="text-xl font-bold mb-6">Menu</h2>
        <ul class="space-y-4">
            <!-- Botões de ação -->
            <li>
                <button class="return w-full text-left px-4 py-2 bg-gray-300 text-black rounded-md shadow hover:bg-gray-400 transition">
                    Voltar
                </button>
            </li>
            <li>
                <button id="btnNovaTask" class="NovaTask w-full text-left px-4 py-2 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 transition">
                    Adicionar Tarefa
                </button>
            </li>
            <li>
                <button class="w-full text-left px-4 py-2 bg-green-500 text-white rounded-md shadow hover:bg-green-600 transition">
                    Ver Tarefas Concluídas
                </button>
            </li>
            <li>
                <button class="w-full text-left px-4 py-2 bg-gray-300 text-black rounded-md shadow hover:bg-gray-400 transition">
                    Exportar Tarefas
                </button>
            </li>
        </ul>

        <!-- Estatísticas -->
        <h2 class="text-xl font-bold mt-8 mb-6">Estatísticas</h2>
        <div class="bg-gray-100 p-4 rounded-lg shadow">
            <p class="font-medium text-lg">Total de Tarefas:</p>
            <p class="text-2xl font-bold">{{ $tarefasNaoConcluidas->count() + $tarefasConcluidas->count() }}</p>

            <p class="font-medium text-lg mt-4">Concluídas:</p>
            <p class="text-2xl font-bold text-green-500">{{ $tarefasConcluidas->count() }}</p>

            <p class="font-medium text-lg mt-4">Pendentes:</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $tarefasNaoConcluidas->count() }}</p>
        </div>
    </aside>

    <!-- Conteúdo Principal -->
    <div class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-3xl font-semibold">Tarefas</h1>
        
        <!-- Tarefas a Fazer -->
        <h2 class="text-xl font-bold mt-8 mb-4">A Fazer</h2>
        @if($tarefasNaoConcluidas->count() > 0)
            <div class="list-group space-y-4">
                @foreach($tarefasNaoConcluidas as $tarefa)
                    <div class="list-group-item bg-white p-4 rounded-md shadow flex justify-between items-center">
                        <div>
                            <h5 class="font-medium text-lg">{{ $tarefa->title }}</h5>
                            <p class="text-sm text-gray-500">
                                <strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($tarefa->start)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="button-container space-x-3">
                            <button class="btn-editar px-4 py-2 bg-yellow-400 text-white font-medium rounded hover:bg-yellow-500 transition"
                                data-tarefa-id="{{ $tarefa->id }}">Editar</button>
                            <button class="btn-concluido px-4 py-2 bg-green-500 text-white font-medium rounded hover:bg-green-600 transition"
                                data-tarefa-id="{{ $tarefa->id }}">Concluir</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning bg-yellow-100 text-yellow-700 p-4 rounded mt-4">
                Não tem tarefas a fazer
            </div>
        @endif

        <!-- Tarefas Concluídas -->
        <h2 class="text-xl font-bold mt-8 mb-4">Concluídas</h2>
        @if($tarefasConcluidas->count() > 0)
            <div class="list-group space-y-4">
                @foreach($tarefasConcluidas as $tarefa)
                    <div class="list-group-item bg-gray-200 p-4 rounded-md shadow flex justify-between items-center">
                        <div>
                            <h5 class="font-medium text-lg">{{ $tarefa->title }}</h5>
                            <p class="text-sm text-gray-500">
                                <strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($tarefa->start)->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="button-container space-x-3">
                            <button class="btn-editar px-4 py-2 bg-yellow-400 text-white font-medium rounded hover:bg-yellow-500 transition"
                                data-tarefa-id="{{ $tarefa->id }}">Editar</button>
                            <button class="btn-desfazer px-4 py-2 bg-red-500 text-white font-medium rounded hover:bg-red-600 transition"
                                data-tarefa-id="{{ $tarefa->id }}">Desfazer</button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning bg-yellow-100 text-yellow-700 p-4 rounded mt-4">
                Não tem tarefas concluídas
            </div>
        @endif
    </div>
</div>
@endsection
