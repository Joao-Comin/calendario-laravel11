<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
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
    @livewireStyles
    <title>Coopeja Calendar</title>
    <style>
        .glass-effect {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans antialiased h-full">
    <input type="hidden" id="userId" value="{{ auth()->id() }}">

    <!-- Modal -->
    <div class="modal-opened hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 glass-effect">
        <div class="modal bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform smooth-transition scale-95">
            <div class="modal-header px-6 py-4 flex justify-between items-center">
                <div class="modal-title">
                    <h3>Cadastrar Eventos</h3>
                </div>
                <div class="modal-close text-white hover:text-gray-200 text-2xl cursor-pointer">&times;</div>
            </div>
            
            <div class="modal-switch flex border-b border-gray-100">
                <button type="button" id="btnEvento" class="flex-1 py-4 font-medium text-blue-600 border-b-2 border-blue-600">Evento</button>
                <button type="button" id="btnTarefa" class="flex-1 py-4 font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent">Tarefa</button>
            </div>
            
            <form action="{{ route('eventos.store') }}" method="post" id="form-add-event" class="smooth-transition">
                @csrf
                <div class="modal-body p-6 space-y-4">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="action" value="">
                    <input type="hidden" name="task" id="task" value="0">
            
                    <div class="space-y-2">
                        <label for="title">Nome do Evento</label>
                        <input type="text" id="title" name="title" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-300 smooth-transition">
                    </div>
                    
                    <div class="space-y-2">
                        <label for="description">Descrição</label>
                        <input type="text" id="description" name="description" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-300 smooth-transition">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="start">Início do Evento</label>
                            <input type="datetime-local" id="start" name="start" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-300 smooth-transition">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="end" id="label-end">Fim do Evento</label>
                            <input type="datetime-local" id="end" name="end" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-300 smooth-transition">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="calendar_id" class="col-sm-2 col-form-label">Calendário:</label>
                        <select name="calendar_id" id="calendar_id" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-200 focus:border-blue-300 smooth-transition">
                            <option value="">Selecione</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer bg-gray-50 px-6 py-4 flex justify-end space-x-3 border-t border-gray-100">
                    <button type="button" class="btn-delete hidden px-5 py-2.5 bg-red-500 text-white rounded-xl hover:bg-red-600 smooth-transition">
                        Excluir
                    </button>
                    <button type="submit" class="btn-save px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 smooth-transition">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @livewireScripts
    <livewire:EventosComponent />
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-72 bg-white/80 glass-effect border-r border-gray-200/50 p-6 flex flex-col space-y-6">
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-800">Olá, {{ auth()->user()->name ?? 'Usuário' }}</h3>
                
                <!-- Modal de Criação de Calendário -->
                <div id="calendarModal" style="display: none;" class="bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-3">
                    <div class="space-y-1">
                        <label for="calendarName" class="block text-sm font-medium text-gray-700">Nome:</label>
                        <input type="text" id="calendarName" placeholder="Nome do calendário" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
            
                    <div class="space-y-1">
                        <label for="type" class="block text-sm font-medium text-gray-700">Agenda:</label>
                        <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="public">Público</option>
                            <option value="private">Privado</option>
                        </select>
                    </div>
                    
                    <div class="space-y-1">
                        <label for="color" id="label-color" class="block text-sm font-medium text-gray-700">Cor do Calendário:</label>
                        <input type="color" id="color" name="color" value="#3b82f6" 
                               class="w-full h-10 border border-gray-300 rounded-md cursor-pointer">
                    </div>
            
                    <div class="flex space-x-2 pt-2">
                        <button id="createCalendarBtn" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Salvar
                        </button>
                        <button id="closeModal" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Fechar
                        </button>
                    </div>
                </div>
                
                <!-- Botão para abrir o formulário -->
                <button id="openModal" class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 smooth-transition flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Criar Calendário
                </button>
            </div>
            
            <!-- Seção de Calendários -->
            <div class="space-y-6">
                <!-- Calendários Privados -->
                <div class="space-y-3">
                    <button onclick="toggleSection('privateCalendars')" class="w-full flex justify-between items-center text-gray-700 font-medium hover:text-gray-900 focus:outline-none">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 group-hover:text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                            </svg>
                            Privado
                        </span>
                        <span id="privateArrow" class="text-gray-500 hover:text-gray-700" style="background: transparent !important; color: #6b7280 !important;">▶</span>
                    </button>
                    <div id="privateCalendars" class="hidden pl-7 space-y-2">
                        <!-- Calendários Privados Dinâmicos -->
                    </div>
                </div>

                <!-- Calendários Públicos -->
                <div class="space-y-3">
                    <button onclick="toggleSection('publicCalendars')" class="w-full flex justify-between items-center text-gray-700 font-medium hover:text-gray-900 focus:outline-none">
                        <span class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 group-hover:text-gray-700" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-1a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v1h-3zM4.75 12.094A5.973 5.973 0 004 15v1H1v-1a3 3 0 013.75-2.906z" />
                            </svg>
                            Público
                        </span>
                        <span id="publicArrow" class="text-gray-500 hover:text-gray-700" style="background: transparent !important; color: #6b7280 !important;">▶</span>
                    </button>
                    <div id="publicCalendars" class="hidden pl-7 space-y-2">
                        <!-- Calendários Públicos Dinâmicos -->
                    </div>
                </div>
            </div>

            <!-- Filtros -->
            <div class="mt-6 space-y-3">
                <h4 class="text-sm font-medium text-gray-700">Filtros</h4>
                <div class="grid grid-cols-3 gap-2">
                    <button id="taskButton" class="px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 smooth-transition text-sm">
                        Tarefas
                    </button>
                    <button id="eventButton" class="px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 smooth-transition text-sm">
                        Eventos
                    </button>
                    <button id="noFilterButton" class="px-3 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 smooth-transition text-sm">
                        Todos
                    </button>
                </div>
            </div>
        </div>

        <!-- Área principal do calendário -->
        <div class="flex-1 p-6">
            <div id="calendar" class="bg-white/80 glass-effect rounded-2xl shadow-sm border border-gray-200/50 overflow-hidden h-full p-4"></div>
        </div>
    </div>
</body>
</html>