document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'standart',
        headerToolbar: {
            left: 'prev,next today TaskButton EventButton',
            center: 'title',
            right: 'dayGridMonth,listWeek,redirectButton'
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
        //api eventos
        events: '/eventos',

        customButtons: {
            redirectButton: {
                text: 'Tarefas',
                click: function() {
                    window.location.href = '/tarefas';
                }
            },
            TaskButton: {
                text: 'Task',
                click: function() {
                    fetch('/eventos?task=1')
                        .then(response => response.json())
                        .then(events => {
                            calendar.removeAllEvents();
                            events.forEach(event => calendar.addEvent(event));
                        })
                        .catch(error => {
                            console.error('Erro ao carregar eventos filtrados:', error);
                        });
                },
                
            },
            EventButton: {
                text: 'Eventos',
                click: function() {
                    fetch('/eventos?task=0')
                        .then(response => response.json())
                        .then(events => {
                            calendar.removeAllEvents();
                            events.forEach(event => calendar.addEvent(event));
                        })
                        .catch(error => {
                            console.error('Erro ao carregar eventos filtrados:', error);
                        });
                },
                
            }
            
        },

        eventDidMount: function(info) {
            console.log(info);
            if (info.event.extendedProps.task) {
                info.el.style.border = '2px dashed blue';
                console.log('finalizado:', info.event.extendedProps.finalizado);

                if (info.event.extendedProps.finalizado == 1) {
                    info.el.style.backgroundColor = 'green';
                    console.log(info.el);
                } else {
                    info.el.style.backgroundColor = 'yellow';
                }

                info.el.style.color = 'black';
            }
        }
    });

    calendar.render();

    const modal = document.querySelector('.modal-opened');

    const abrirModal = (info) => {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.style.transition = 'opacity 300ms';
            setTimeout(() => {
                modal.style.opacity = 1;
            }, 100);
        }

        document.querySelector('.modal-title h3').innerText = 'Cadastrar Evento';
        document.querySelector('#id').value = '';
        document.querySelector('#title').value = '';
        document.querySelector('#description').value = '';
        document.querySelector('#color').value = '#000000';
        document.querySelector('#start').value = info.dateStr + "T08:00";
        document.querySelector('#end').value = info.dateStr + "T18:00";
        document.querySelector('#task').value = "0";
        document.querySelector("#user_id").value = "";

        const SelecionarUser = document.querySelector('#user_id');
        SelecionarUser.innerHTML = '<option value="">Selecione</option>';

        fetch('/eventos/usuarios')
        .then(response => {
        if (!response.ok) { 
            throw new Error('Erro ao carregar usuários: ' + response.statusText);
        }
        return response.json();
    })
    .then(users => {
        users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = user.name;
            SelecionarUser.appendChild(option);
        });
    })
    };

    const abrirModalEditar = (info) => {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.style.transition = 'opacity 300ms';
            setTimeout(() => {
                modal.style.opacity = 1;
            }, 100);
        }
        let data_start = [
            info.event.start.toLocaleString().replace(',', '').split(' ')[0].split('/').reverse().join('-'),
            info.event.start.toLocaleString().replace(',', '').split(' ')[1]
        ].join(' ');
        let data_end = info.event.end ? [
            info.event.end.toLocaleString().replace(',', '').split(' ')[0].split('/').reverse().join('-'),
            info.event.end.toLocaleString().replace(',', '').split(' ')[1]
        ].join(' ') : '';

        document.querySelector('.modal-title h3').innerHTML = 'Editar';
        document.querySelector('#id').value = info.event.id;
        document.querySelector('#title').value = info.event.title;
        document.querySelector('#description').value = info.event.extendedProps.description;
        document.querySelector('#color').value = info.event.backgroundColor;
        document.querySelector('#start').value = data_start;
        document.querySelector('#end').value = data_end;
        document.querySelector('#task').value = info.event.extendedProps.task == 1 || info.event.extendedProps.task === "1" ? "1" : "0";
        document.querySelector('#user_id').value = info.event.extendedProps.user_id;
        document.querySelector('.btn-delete').classList.remove('hidden');

        const SelecionarUser = document.querySelector('#user_id');
    SelecionarUser.innerHTML = '<option value="">Selecione</option>'; 

    fetch('/eventos/usuarios') 
    .then(response => {
        if (!response.ok) { 
            throw new Error('Erro ao carregar usuários: ' + response.statusText);
        }
        return response.json();
    })
    .then(users => {
        users.forEach(user => {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = user.name;
            
            if (user.id == info.event.extendedProps.user_id) {
                option.selected = true;  
            }
            SelecionarUser.appendChild(option);
        });
    })

        if (info.event.extendedProps.task == 1 || info.event.extendedProps.task === "1") {
            btnTarefa.click();
        } else {
            btnEvento.click();
        }
    };

    const moverEvento = (info) => {
        console.log(info);
        let id = info.event.id;
        let start = info.event.startStr;
        let end = info.event.endStr;
        let color = info.event.backgroundColor;
        let title = info.event.title;
        let description = info.event.description;
        let task = info.event.extendedProps.task;
        let finalizado = info.event.extendedProps.finalizado;
        let user_id = info.event.extendedProps.user_id;
        

        let data = { id, title, color, start, end, description, task, finalizado, user_id };

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
                console.log(data);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: 'Não foi possível atualizar o evento.',
                });
            }
        })
        .catch(error => console.error('Erro:', error));
    };

    document.querySelector('.modal-close').addEventListener('click', () => fecharModal());
    modal.addEventListener('click', function(event) {
        if (event.target === this) {
            fecharModal();
        }
    });
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            fecharModal();
        }
    });

    function fecharModal() {
        modal.style.transition = 'opacity 300ms';
        modal.style.opacity = 0;
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
});

//requisição de envio do formulário
document.querySelector('#form-add-event').addEventListener('submit', function(event) {
    event.preventDefault();

    let title = document.querySelector('#title');
    let description = document.querySelector('#description');
    let start = document.querySelector('#start');
    let end = document.querySelector('#end');
    let color = document.querySelector('#color');
    let eventId = document.querySelector('#id').value.trim();
    let isTask = document.querySelector('#task').checked ? 1 : 0;
    let user_id = document.querySelector('#user_id');

    if (title.value == '') {
        Swal.fire({ icon: 'error', title: 'Campo Obrigatório!', text: 'O nome do evento deve ser preenchido.' });
        title.style.borderColor = 'red';
        title.focus();
        return false;
    }
    if (start.value == '') {
        Swal.fire({ icon: 'error', title: 'Campo Obrigatório!', text: 'A data de início do evento deve ser preenchida.' });
        start.style.borderColor = 'red';
        start.focus();
        return false;
    }

    let isEditing = eventId !== '';
    let mensagem = isEditing ? "Evento Atualizado!" : "Evento Criado!";
    let formData = new FormData(this);
    formData.set('task', isTask);

    Swal.fire({
        icon: 'success',
        title: mensagem,
        text: `O evento foi ${isEditing ? 'atualizado' : 'criado'} com sucesso!`,
        timer: 2000,
        showConfirmButton: false
    }).then(() => {
        this.submit();
    });
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

document.addEventListener('DOMContentLoaded', function() {
    const btnEvento = document.getElementById('btnEvento');
    const btnTarefa = document.getElementById('btnTarefa');
    const inputTask = document.getElementById('task');
    const labelColor = document.getElementById('label-color');
    const inputColor = document.getElementById('color');
    const labelEnd = document.getElementById('label-end');
    const inputEnd = document.getElementById('end');

    btnEvento.addEventListener('click', function() {
        btnEvento.classList.add('active');
        btnTarefa.classList.remove('active');
        inputTask.value = "0";
        labelColor.style.display = 'block';
        inputColor.style.display = 'block';
        labelEnd.style.display = 'block';
        inputEnd.style.display = 'block';
    });

    btnTarefa.addEventListener('click', function() {
        btnEvento.classList.remove('active');
        btnTarefa.classList.add('active');
        inputTask.value = "1";
        labelColor.style.display = 'none';
        inputColor.style.display = 'none';
        labelEnd.style.display = 'none';
        inputEnd.style.display = 'none';
    });

    btnEvento.click();
});
