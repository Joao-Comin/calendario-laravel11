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
<body>

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

              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn-save">Salvar</button>
                  <button type="button" class="btn-delete hidden">Excluir</button>
              </div>
          </form>
        </div>
      </div>
    
    @if (session('error'))
        <div id="error-message" class="alert-danger">{{ session('error') }}</div>
    @endif
    
    <div class="calendar-area">
      <h3>Agenda e Full Calendar</h3>
      <div id="calendar"></div>
    </div>

    
</body>
</html>