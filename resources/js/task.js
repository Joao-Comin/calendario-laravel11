
document.addEventListener('DOMContentLoaded', function() {
    const btnConcluidos = document.querySelectorAll('.btn-concluido');
    
    btnConcluidos.forEach(function(btnConcluido) {
        btnConcluido.addEventListener('click', function() {
            const tarefaID = this.getAttribute('data-tarefa-id');  
            console.log(tarefaID); 
            
            Swal.fire({
                title: 'Deseja Concluir?',
                text: "A tarefa será marcada como concluída!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2cc91e',
                cancelButtonColor: '#e60b0b.',
                confirmButtonText: 'Sim, concluir!',
                cancelButtonText: 'Cancelar'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    fetch(`/tarefas/${tarefaID}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Concluído!',
                                'Tarefa concluída com sucesso.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                'Não foi possível concluir a tarefa.',
                                'error'
                            );
                        }
                    })
                    .catch(error => console.error('Erro:', error));
                }
            });
        });
    });
});

const btnEditar = document.querySelectorAll('.btn-editar');
btnEditar.forEach(function(btn) {
    btn.addEventListener('click', function() {
        const tarefaID = this.getAttribute('data-tarefa-id');
        fetch(`/tarefas/editar/${tarefaID}`)
            .then(response => response.json())
            .then(data => {
                abrirModalEditarEvento(data);
            })
            .catch(error => console.error('Erro:', error));
    });
});

const abrirModalEditarEvento = (evento) => {
    console.log(evento);
    const modal = document.querySelector('.modal');
    const teste = document.querySelector('.modal-opened');
    console.log(teste);

    if (teste && teste.classList.contains('hidden')) {
        teste.classList.remove('hidden');
        modal.classList.remove('width');
        modal.style.display = 'block';
        teste.style.transition = 'opacity 300ms';
        setTimeout(() => {
            teste.style.opacity = 1;
        }, 100);
        
    } else {
        console.log('Modal não encontrado ou erro ao abrir.');
    }

    let data_start = [
        new Date(evento.start).toLocaleString().replace(',','').split(' ')[0].split('/').reverse().join('-'),
        new Date(evento.start).toLocaleString().replace(',','').split(' ')[1]
    ].join(' ');

    let data_end = evento.end ? [
        new Date(evento.end).toLocaleString().replace(',','').split(' ')[0].split('/').reverse().join('-'),
        new Date(evento.end).toLocaleString().replace(',','').split(' ')[1]
    ].join(' ') : '';

    document.querySelector('.modal-title h3').innerText = 'Editar Evento';
    document.querySelector('#id').value = evento.id;
    document.querySelector('#title').value = evento.title;
    document.querySelector('#description').value = evento.description;
    document.querySelector('#color').value = evento.color;
    document.querySelector('#start').value = data_start;
    document.querySelector('#end').value = data_end;
    document.querySelector('#task').value = evento.task == 1 || evento.task === "1" ? "1" : "0";
    document.querySelector('.btn-delete').classList.remove('hidden');

    if (evento.task == 1 || evento.task === "1") {
        btnTarefa.click();
    } else {
        btnEvento.click();
    }
};

const modalCloseButton = document.querySelector('.modal-close');
if (modalCloseButton) {
    modalCloseButton.addEventListener('click', () => {
        const modal = document.querySelector('.modal-opened');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.opacity = 0;
        }
    });
}
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

    const btnDesfazer = document.querySelectorAll('.btn-desfazer');
    
    btnDesfazer.forEach(function(btnDesfazer) {
        btnDesfazer.addEventListener('click', function() {
            const tarefaID = this.getAttribute('data-tarefa-id');  
            console.log(tarefaID); 
            
            Swal.fire({
                title: 'Deseja Desfazer?',
                text: "A tarefa voltará para a lista de tarefas pendentes!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2cc91e',
                cancelButtonColor: '#e60b0b.',
                confirmButtonText: 'Sim, desfazer!',
                cancelButtonText: 'Cancelar'
            })
            .then((result) => {
                if (result.isConfirmed) {
                    fetch(`/tarefas/desfazer/${tarefaID}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Desfeito!',
                                'Tarefa Desfeita com sucesso.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Erro!',
                                'Não foi possível desfazer a tarefa.',
                                'error'
                            );
                        }
                    })
                    .catch(error => console.error('Erro:', error));
                }
            });
        });
    });
    
    btnEvento.click();

    // Adicionar evento de clique ao botão "Add Tarefa"
    const btnNovaTask = document.getElementById('btnNovaTask');
    if (btnNovaTask) {
        btnNovaTask.addEventListener('click', function() {
            btnTarefa.click();
            const modal = document.querySelector('.modal-opened');
            const teste = document.querySelector('.modal');
            console.log(modal);
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.opacity = 1;
                teste.style.display = 'block';
                inputTask.value = "1";
            }
        });
    }
