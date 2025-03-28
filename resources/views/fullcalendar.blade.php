<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css'])
    @vite(['resources/js/calendar.js'])
    <script src="/dist/index.global.min.js"></script>
    <script src="/core/locales/pt-br.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Calender</title>
    

</head>
<input type="hidden" id="userId" value="{{ auth()->id() }}">


<div class="modal-opened hidden">
    <div class="modal">
      <div class="modal-header">
          <div class="modal-title">
              <h3>Cadastrar Eventos</h3>
          </div>
          <div class="modal-close">x</div>
      </div>
      <div class="modal-switch">
          <button type="button" id="btnEvento" class="active">Evento</button>
          <button type="button" id="btnTarefa">Tarefa</button>
      </div>
      <form action="{{ route('eventos.store') }}" method="post" id="form-add-event">
          @csrf
          <div class="modal-body">
              <input type="hidden" name="id" id="id">
              <input type="hidden" name="action" value="">
              <input type="hidden" name="task" id="task" value="0">
      
              <label for="title">Nome do Evento</label>
              <input type="text" id="title" name="title" value="{{ old('title') }}">
             
              <label for="description">Descrição</label>
              <input type="text" id="description" name="description" value="{{ old('description') }}">
      
              <label for="color" id="label-color">Selecione uma cor</label>
              <input type="color" id="color" name="color" value="{{ old('color') }}">
      
              <label for="start">Início do Evento</label>
              <input type="datetime-local" id="start" name="start" value="{{ old('start') }}">
      
              <label for="end" id="label-end">Fim do Evento</label>
              <input type="datetime-local" id="end" name="end" value="{{ old('end') }}">

              <label for="calendar_id" class="col-sm-2 col-form-label">Calendario:</label>
               <select name="calendar_id" id="calendar_id" class="form-control">
                   <option value="">Selecione</option>
               </select>

          </div>
          <div class="modal-footer">
              <button type="submit" class="btn-save">Salvar</button>
              <button type="button" class="btn-delete hidden">Excluir</button>
          </div>
      </form>
    </div>
  </div>

<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg p-5 flex flex-col space-y-4">
            <h3 class="text-lg font-semibold">Bem-vindo, {{ auth()->user()->name ?? 'Usuário' }}</h3>
            
            <!-- Formulário de Criação de Calendário -->
            <div id="calendarModal" style="display: none;" class="bg-gray-100 p-4 rounded-lg shadow">
                <label for="calendarName" class="block text-sm font-medium text-gray-700 mb-2">Nome:</label>
                <input type="text" id="calendarName" placeholder="Nome do calendário" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-200 focus:border-blue-500">

                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Agenda:</label>
                <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-200 focus:border-blue-500">
                    <option value="public">Público</option>
                    <option value="private">Privado</option>
                </select>

                <button id="createCalendarBtn" class="w-full px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition mt-4">
                    Salvar
                </button>
                <button id="closeModal" class="w-full px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition mt-2">
                    Fechar
                </button>
            </div>

            <!-- Botão para abrir o formulário -->
            <button id="openModal" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md shadow hover:bg-blue-700 transition duration-300">
                Criar Calendário
            </button>

            <!-- Seleção de Calendários -->
            <div id="calendarCheckboxes" class="mt-6 bg-gray-100 p-4 rounded-lg shadow">
                <h4 class="text-lg font-bold mb-4">Selecione um Calendário</h4>
                <div class="space-y-3">
                    <div>
                        <input type="checkbox" id="calendar-1" value="1" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="calendar-1" class="ml-2 text-gray-700">Calendário 1</label>
                    </div>
                    <div>
                        <input type="checkbox" id="calendar-2" value="2" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="calendar-2" class="ml-2 text-gray-700">Calendário 2</label>
                    </div>
                </div>
            </div>


            <!-- Filtros -->
            <h4 class="text-sm font-medium">Filtros</h4>
            <button id="taskButton" class="w-full py-2 px-4 bg-blue-500 text-white rounded hover:bg-blue-600">Tarefas</button>
            <button id="eventButton" class="w-full py-2 px-4 bg-green-500 text-white rounded hover:bg-green-600">Eventos</button>
            <button id="noFilterButton" class="w-full py-2 px-4 bg-gray-300 text-black rounded hover:bg-gray-400">Sem Filtro</button>
        </div>

        <!-- Área principal do calendário -->
        <div class="flex-1 p-5">
            <div id="calendar" class="bg-white shadow-md rounded p-5"></div>
        </div>
    </div>
</body>
</html>
