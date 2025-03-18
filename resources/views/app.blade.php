<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tasks</title>
    @vite(['resources/css/style.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="modal-opened hidden">
        <div class="modal">
          <div class="modal-header">
              <div class="modal-title">
                  <h3>Cadastrar</h3>
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
          
                  <label for="title">Nome</label>
                  <input type="text" id="title" name="title" value="{{ old('title') }}">
                 
                  <label for="description">Descrição</label>
                  <input type="text" id="description" name="description" value="{{ old('description') }}">
          
                  <label for="color" id="label-color">Selecione uma cor</label>
                  <input type="color" id="color" name="color" value="{{ old('color') }}">
          
                  <label for="start">Início do Evento</label>
                  <input type="datetime-local" id="start" name="start" value="{{ old('start') }}">
          
                  <label for="end" id="label-end">Fim do Evento</label>
                  <input type="datetime-local" id="end" name="end" value="{{ old('end') }}">
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn-save">Salvar</button>
                  <button type="button" class="btn-delete hidden">Excluir</button>
              </div>
          </form>
        </div>
      </div>
    <main>
        @yield('content')
        
    </main>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.querySelector('.return');
            if (btn) {
                btn.addEventListener('click', () => {
                    window.location.href = '/';
                });
            }
        });
    </script>
    @vite(['resources/js/task.js'])
</body>
</html>