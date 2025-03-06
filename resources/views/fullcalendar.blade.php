<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css'])

    <title>Calendar</title>
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
        <form action="{{ route('eventos.store') }}" method="post" id="form-add-event">
          @csrf
          <div class="modal-body">
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="action" value=""> 

            <label for="title">Nome do Evento</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}">

            <label for="color">Selecione uma cor</label>
            <input type="color" id="color" name="color" value="{{ old('color') }}">

            <label for="start">Início do Evento</label>
            <input type="datetime-local" id="start" name="start" value="{{ old('start') }}">

            <label for="end">Fim do Evento</label>
            <input type="datetime-local" id="end" name="end" value="{{ old('end') }}">
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

    <script src="/dist/index.global.min.js"></script>
    <script src="/core/locales/pt-br.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                navLinks: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                dateClick: function(info) {
                    abrirModal(info);
                },
                eventClick: function(info) {
                    abrirModalEditar(info);
                },
                eventDrop: function(info) {
                    moverEvento(info);
                },
                eventResize: function(info) {
                    moverEvento(info);
                },
                events: '/eventos',
            });
            calendar.render();
            const modal = document.querySelector('.modal-opened');

            const abrirModal = (info) => {
                if(modal.classList.contains('hidden')){
                    modal.classList.remove('hidden');

                    modal.style.transition = 'opacity 300ms';
                    setTimeout(() => {
                        modal.style.opacity = 1;
                    },100);
                }
                const startInput = document.querySelector('#start');
                const endInput = document.querySelector('#end');
                if (startInput && endInput) { 
                    startInput.value = info.dateStr + "T08:00"; 
                    endInput.value = info.dateStr + "T18:00";
                }
            }

            const abrirModalEditar = (info) => {
                if(modal.classList.contains('hidden')){
                    modal.classList.remove('hidden');

                    modal.style.transition = 'opacity 300ms';
                    setTimeout(() => {
                        modal.style.opacity = 1;
                    },100);
                }
                let data_start = [
                    info.event.start.toLocaleString().replace(',','').split(' ')[0].split('/').reverse().join('-'),
                    info.event.start.toLocaleString().replace(',','').split(' ')[1]
                ].join(' ');
                let data_end = [
                    info.event.end.toLocaleString().replace(',','').split(' ')[0].split('/').reverse().join('-'),
                    info.event.end.toLocaleString().replace(',','').split(' ')[1]
                ].join(' ');
                console.log(data_start);
                console.log(data_end);
                document.querySelector('.modal-title h3').innerHtml = 'Editar Evento';
                document.querySelector('#id').value = info.event.id;
                document.querySelector('#title').value = info.event.title;
                document.querySelector('#color').value = info.event.backgroundColor;
                document.querySelector('#start').value = data_start;
                document.querySelector('#end').value = data_end;
                document.querySelector('.btn-delete').classList.remove('hidden');
            }

            const moverEvento = (info) => {
                let id = info.event.id;
                let start = info.event.startStr;
                let end = info.event.endStr;
                let color = info.event.backgroundColor;
                let title = info.event.title;

                let data = {id, title, color, start, end};

                fetch(`/eventos/${id}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Evento Atualizado!',
                            text: 'O evento foi atualizado com sucesso!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Não foi possível atualizar o evento.',
                        });
                    }
                })
                .catch(error => console.error('Erro:', error));
            }

            document.querySelector('.modal-close').addEventListener('click', () => fecharModal());
            modal.addEventListener('click', function(event) {
                if(event.target === this){
                    fecharModal();
                }
            });
            document.addEventListener('keydown', function(event) {
                if(event.key === 'Escape'){
                    fecharModal();
                }
            });
            
            function fecharModal(){
                modal.style.transition = 'opacity 300ms';
                modal.style.opacity = 0;
                setTimeout(() => {
                    modal.classList.add('hidden');
                },300);
            }
        });

        document.querySelector('#form-add-event').addEventListener('submit', function(event) {
            event.preventDefault();
            let title = document.querySelector('#title');
            let start = document.querySelector('#start');
            let end = document.querySelector('#end');
            let color = document.querySelector('#color');

            if (title.value == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo Obrigatório!',
                    text: 'O nome do evento deve ser preenchido.',
                });
                title.style.borderColor = 'red';
                title.focus();
                return false;
            }
            if (start.value == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo Obrigatório!',
                    text: 'A data de início do evento deve ser preenchida.',
                });
                start.style.borderColor = 'red';
                start.focus();
                return false;
            }
            if (end.value == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Campo Obrigatório!',
                    text: 'A data de fim do evento deve ser preenchida.',
                });
                end.style.borderColor = 'red';
                end.focus();
                return false;
            }
            Swal.fire({
                icon: 'success',
                title: 'Evento Criado!',
                text: 'O evento foi criado com sucesso!',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.reload(); 
            });

            this.submit();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const btnDelete = document.querySelector('.btn-delete');
            
            btnDelete.addEventListener('click', function() {
                const eventId = document.querySelector('#id').value;

                if (!eventId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Nenhum evento selecionado!',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Essa ação não pode ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/eventos/${eventId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Excluído!',
                                    text: 'O evento foi removido com sucesso!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                
                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro!',
                                    text: 'Não foi possível excluir o evento.',
                                });
                            }
                        })
                        .catch(error => console.error('Erro:', error));
                    }
                });
            });
        });
    </script>
</body>
</html>