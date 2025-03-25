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

              <label for="user_id" class="col-sm-2 col-form-label">Usuário</label>
              <select name="user_id" id="user_id" class="form-control">
                  <option value="">Selecione</option>
              </select>

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
            
            <!-- Seleção de calendário -->
            <label for="calendarioSelect" class="text-sm font-medium">Selecionar Calendário:</label>
            <select id="calendarioSelect" class="w-full p-2 border rounded">
                <option value="4">Geral</option>
                <option value="1">Privado</option>
                <option value="2">Público</option>
                <option value="3">Grupo 1</option>
                <option value="4">Grupo 2</option>
            </select>

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
