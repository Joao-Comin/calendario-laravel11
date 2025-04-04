const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
  
  window.toggleSection = function (sectionId) {
    console.log("Toggling section:", sectionId);
    
    let section = document.getElementById(sectionId);
    let arrow = document.getElementById(sectionId === 'privateCalendars' ? 'privateArrow' : 'publicArrow');

    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        arrow.textContent = '🔽';
    } else {
        section.classList.add('hidden');
        arrow.textContent = '▶';
    }
};
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("privateCalendars").classList.remove("hidden");
    document.getElementById("publicCalendars").classList.add("hidden");
    document.getElementById("privateArrow").textContent = "🔽"; 
    document.getElementById("publicArrow").textContent = "▶";
});

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'standard', 
        headerToolbar: {
            left: 'prev,next today',
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
            console.log('info cliquei: ', info);
            
        },
        eventDrop: function(info) {
            moverEvento(info);
        },
        eventResize: function(info) {
            moverEvento(info);
        },
        events: '/eventos',
        
        eventDidMount: function(info) {
            console.log("📢 Evento carregado no calendário:", info.event);
            
            if (!info.event.id) {
                console.error("🚨 ERRO: Evento sem ID!", info.event);
            }
        },

        customButtons: {
            redirectButton: {
                text: 'Tarefas',
                click: function() {
                    window.location.href = '/tarefas';
                }
            },
        },

        eventDidMount: function(info) {
            if (info.event.extendedProps.task) {
                info.el.style.border = '2px dashed blue';
                if (info.event.extendedProps.finalizado == 1) {
                    info.el.style.backgroundColor = 'green';
                } else {
                    info.el.style.backgroundColor = 'yellow';
                }
                info.el.style.color = 'black';
            }
        }
        
    });
    
    calendar.render();
    
    //função para os filtros de tarefas, eventos e sem filtro
    function Filtro(url) {
        //console.log(url);
        
        fetch(url)
            .then(response => response.json())
            .then(events => {
                calendar.removeAllEvents(); 
                events.forEach(event => calendar.addEvent(event));
            })
            .catch(error => {
                console.error('Erro ao carregar eventos filtrados:', error);
            });
    }

    const taskButton = document.getElementById("taskButton");
    const eventButton = document.getElementById("eventButton");
    const noFilterButton = document.getElementById("noFilterButton");

    taskButton.addEventListener("click", function () {
        //console.log('entrou aki');
        
        Filtro('/eventos?task=1');
    });

    eventButton.addEventListener("click", function () {
        //console.log('entrou 0');
        
        Filtro('/eventos?task=0'); 
    });

    noFilterButton.addEventListener("click", function () {
        Filtro('/eventos'); 
    });

});

    //abrir o modal de criação de eventos/tarefas
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
        document.querySelector('#end').value = info.dateStr;
        document.querySelector('#task').value = "0";
        document.querySelector('#calendar_id').value = "";
    
        Livewire.on('CalendariosCarregados', function (data) {
            if (!Array.isArray(data) || data.length === 0) {
                console.error("❌ ERRO: `data` não é um array válido!");
                return;
            }
        
            const responseData = data[0];
        
            console.log("🔍 Verificando chaves disponíveis:", Object.keys(responseData));
        
            const calendarSelect = document.getElementById('calendar_id');
            calendarSelect.innerHTML = '<option value="">Selecione</option>';
        
            // Tenta acessar responseData.calendarios corretamente
            if (responseData.hasOwnProperty('calendarios') && Array.isArray(responseData.calendarios)) {
                console.log("✅ `calendarios` encontrado!", responseData.calendarios);
        
                responseData.calendarios.forEach(function (calendario) {
                    const option = document.createElement('option');
                    option.value = calendario.id;
                    option.textContent = calendario.name;
                    console.log(`📌 Adicionando calendário: ID=${calendario.id}, Nome=${calendario.name}`);
                    calendarSelect.appendChild(option);
                });
        
            } 
            if (responseData.default_calendar) {
                calendarSelect.value = responseData.default_calendar.id || "";
            }
        });
        
        
        // Dispara o evento para pegar os calendários
        console.log('Disparando Livewire.dispatch("pegarCalendarios")');
        Livewire.dispatch('pegarCalendarios');
    };
    

      
        
            

    //abre o modal de editar o evento/tarefa
    const abrirModalEditar = (info) => {
        console.log(info);
        
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
        ].join('T');
        let data_end = info.event.end ? [
            info.event.end.toLocaleString().replace(',', '').split(' ')[0].split('/').reverse().join('-'),
            info.event.end.toLocaleString().replace(',', '').split(' ')[1]
        ].join('T') : '';

        console.log(data_start);
        console.log("📢 ID do evento recebido:", info.event);
        document.querySelector('.modal-title h3').innerHTML = 'Editar Eventos';
        document.querySelector('#id').value = info.event.id;
        document.querySelector('#title').value = info.event.title;
        document.querySelector('#description').value = info.event.extendedProps.description;
        document.querySelector('#color').value = info.event.backgroundColor;
        document.querySelector('#start').value = data_start;
        document.querySelector('#end').value = data_end;
        document.querySelector('#task').value = info.event.extendedProps.task == 1 || info.event.extendedProps.task === "1" ? "1" : "0";
        document.querySelector('#calendar_id').value = info.event.extendedProps.calendar_id;
        document.querySelector('.btn-delete').classList.remove('hidden');

        let eventoCalendarId = info.event ? info.event.extendedProps.calendar_id : null;
        Livewire.dispatch('pegarCalendarios');

        Livewire.on('CalendariosCarregados', function (data) {
            console.log('📩 Dados Recebidos do Livewire:', data);
        
            if (!Array.isArray(data) || !data[0].calendarios) {
                console.warn("⚠️ `calendarios` não encontrado ou não é um array!");
                return;
            }
        
            const calendarSelect = document.getElementById('calendar_id');
            calendarSelect.innerHTML = '<option value="">Selecione</option>';
        
            data[0].calendarios.forEach(function (calendario) {
                const option = document.createElement('option');
                option.value = calendario.id;
                option.textContent = calendario.name;
                calendarSelect.appendChild(option);
            });
        
            
            if (eventoCalendarId) {
                calendarSelect.value = eventoCalendarId;
                console.log(`✅ Selecionado o calendário do evento: ${eventoCalendarId}`);
            } else if (data[0].default_calendar_id) {
                calendarSelect.value = data[0].default_calendar_id;
                console.log(`✅ Selecionado calendário padrão: ${data[0].default_calendar_id}`);
            }
        });        
};
        
    
           
            

    //faz com que consiga mover e salvar no banco a modificação do evento em drag and drop
    const moverEvento = (info) => {
        let id = info.event.id;
        let start = info.event.startStr;
        let end = info.event.endStr;
        let color = info.event.backgroundColor;
        let title = info.event.title;
        let description = info.event.extendedProps.description;
        let task = info.event.extendedProps.task;
        let finalizado = info.event.extendedProps.finalizado;
        let calendar_id = info.event.extendedProps.calendar_id;
    
        let data_start = [
            info.event.start.toLocaleString().replace(',', '').split(' ')[0].split('/').reverse().join('-'),
            info.event.start.toLocaleString().replace(',', '').split(' ')[1]
        ].join('T');
    
        let data_end = info.event.end ? [
            info.event.end.toLocaleString().replace(',', '').split(' ')[0].split('/').reverse().join('-'),
            info.event.end.toLocaleString().replace(',', '').split(' ')[1]
        ].join('T') : null ;
    
        let data = {
            id,
            title,
            color,
            start: data_start,
            end: data_end,
            description,
            task,
            finalizado,
            calendar_id,
        };
        console.log(data);
        
        Livewire.dispatch('moverEvento', [data]);
    };
    Livewire.on('eventoAtualizado', function (data) {
        Toast.fire({
            icon: "success",
            title: 'Evento Movido Com Sucesso'
        });
    });
    

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
    let calendar_id = document.querySelector('#calendar_id');  

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

//delete de eventos
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
                Livewire.dispatch('deletarEvento', [eventId]);
                console.log(eventId);
                
                
            }
        });
    });
    Livewire.on('EventoDeletado', function (data) {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: 'Evento deletado com sucesso',
            
        }).then(() => {
            location.reload();
        });
    });
});




//bloquear os campos no modal de criação/edição de uma task
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

document.addEventListener('DOMContentLoaded', function () {
    Livewire.on('calendarioCriado', function () {
        console.log('🔥 Calendário Criado!');

        Swal.fire({
            icon: 'success',
            title: 'Criado',
            text: 'Calendário Criado Com Sucesso',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.reload();
        });

        document.getElementById("calendarModal").style.display = "none";
        document.getElementById("calendarName").value = "";
        document.getElementById("type").value = "";
    });
});


// 📌 Modal create calendário
document.getElementById('openModal').addEventListener("click", function () {
    document.getElementById("calendarModal").style.display = "block";
});

document.getElementById('closeModal').addEventListener("click", function () {
    document.getElementById("calendarModal").style.display = "none";
});

document.getElementById("createCalendarBtn").addEventListener("click", function () {
    let calendarName = document.getElementById("calendarName").value;
    let type = document.getElementById("type").value;
    let color = document.getElementById("color").value;
    let calendario = [];
    calendario.push({
        name: calendarName,
        type: type,
        color: color
    });

    console.log(calendarName);
    console.log(type);
    console.log(color);
    

    Livewire.dispatch('criarCalendario', [calendario]);
});

//pegar calendarios do usuario
const userId = document.getElementById('userId').value;

Livewire.dispatch('pegarCalendariosUsuario');

Livewire.on('CalendariosUsuarioCarregados', function (data) {
    console.log("📩 Dados Recebidos do Livewire:", data);
    //ta vindo em 2 arrays deixar esse data[0] para o data
    if (Array.isArray(data) && Array.isArray(data[0])) {
        data = data[0];
    }

    if (!Array.isArray(data)) {
        console.error("❌ 'calendarios' não encontrado ou não é um array válido!", data);
        return;
    }
    // Pegando os containers do HTML para público e privado
    const publicCalendars = document.getElementById('publicCalendars');
    const privateCalendars = document.getElementById('privateCalendars');

    // Limpando os containers antes de adicionar novos itens
    publicCalendars.innerHTML = '';
    privateCalendars.innerHTML = '';

    data.forEach(calendar => {
        const calendarItem = document.createElement('div');
        calendarItem.classList.add("flex", "items-center", "justify-between", "group", "hover:bg-gray-50", "p-", "rounded");
        
        // Container do checkbox e label
        const checkboxContainer = document.createElement('div');
        checkboxContainer.classList.add("flex", "items-center", "space-x-2", "flex-1");
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `calendar-${calendar.id}`;
        checkbox.value = calendar.id;
        checkbox.checked = true;
        checkbox.classList.add("w-4", "h-4", "text-blue-600", "focus:ring-blue-500", "border-gray-300", "rounded");
        
        const label = document.createElement('label');
        label.htmlFor = `calendar-${calendar.id}`;
        label.textContent = calendar.name;
        label.classList.add("ml-2", "text-gray-700", "flex-1");
        
        checkboxContainer.appendChild(checkbox);
        checkboxContainer.appendChild(label);
        
        // Adiciona o símbolo ⁝ (três pontos verticais)
        const menuButton = document.createElement('button');
        menuButton.innerHTML = '︙';
        menuButton.classList.add("text-gray-400", "hover:text-gray-600", "text-lg", "font-light", 
                               "opacity-0", "group-hover:opacity-100", "transition-opacity", 
                               "cursor-pointer", "px-2", "py-1");
        menuButton.title = "Opções";
        menuButton.onclick = function(e) {
            e.stopPropagation(); // Impede que o clique afete o checkbox
            abrirMenuCalendario(calendar.id);
        };
        
        calendarItem.appendChild(checkboxContainer);
        calendarItem.appendChild(menuButton);
        
        // Adiciona ao local correto (privado ou público)
        if (calendar.type === "private") {
            privateCalendars.appendChild(calendarItem);
        } else {
            publicCalendars.appendChild(calendarItem);
        }
    });

        // Evento de clique para carregar/remover eventos ao marcar/desmarcar
        checkbox.addEventListener("change", function () {
            if (this.checked) {
                console.log(`✅ Checkbox ${this.value} marcado. Carregando eventos.`);
                Livewire.dispatch('carregarEventos', { calendar_id: this.value });
            } else {
                console.log(`❌ Checkbox ${this.value} desmarcado. Removendo eventos.`);
                Livewire.dispatch('removerEventosPorCalendario', { calendar_id: this.value });
            }
        

        // Carrega os eventos dos calendários já marcados por padrão
        if (checkbox.checked) {
            Livewire.dispatch('carregarEventos', { calendar_id: checkbox.value });
        }
    });
});






    
  














