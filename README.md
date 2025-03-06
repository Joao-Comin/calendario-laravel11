
# Calendário de Eventos com Laravel 11 e FullCalendar

Este é um projeto de calendário de eventos desenvolvido com Laravel e FullCalendar. Ele permite criar, editar, mover e excluir eventos em um calendário interativo.

## Funcionalidades

- Visualização de eventos em um calendário
- Criação de novos eventos
- Edição de eventos existentes
- Movimentação e redimensionamento de eventos
- Exclusão de eventos

## Tecnologias Utilizadas

- [Laravel](https://laravel.com/) - Framework PHP
- [FullCalendar](https://fullcalendar.io/) - Biblioteca JavaScript para calendários
- [SweetAlert2](https://sweetalert2.github.io/) - Biblioteca JavaScript para alertas 
- [Ajax](https://api.jquery.com/category/ajax/) - requisições

## Requisitos

- PHP >= 8.0
- Composer
- Node.js e npm
- Banco de dados MySQL

## Instalação

Siga os passos abaixo para configurar e executar o projeto localmente.

### 1. Clone o repositório

```bash
git clone https://github.com/Joao-Comin/calendario-laravel11.git
cd calendario-laravel11
```

### 2. Instale as dependências do PHP

```bash
composer install
```

### 3. Instale as dependências do Node.js

```bash
npm install
```

### 4. Configure o arquivo `.env`

Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, especialmente as configurações do banco de dados.
Edite o arquivo `.env` e configure as seguintes variáveis:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 6. Execute as migrações do banco de dados

```bash
php artisan migrate
```

### 7. Compile os assets

```bash
npm run dev
```

### 8. Inicie o servidor de desenvolvimento

```bash
php artisan serve
```

### 9. Acesse a aplicação

Abra o navegador e acesse `http://localhost:8000` para ver o calendário de eventos.

## Uso

### Criar um Evento

1. Clique em uma data no calendário.
2. Preencha os campos do formulário.
3. Clique em "Salvar".

### Editar um Evento

1. Clique em um evento existente no calendário.
2. Edite os campos do formulário.
3. Clique em "Salvar".

### Mover ou Redimensionar um Evento

1. Arraste um evento para uma nova data ou redimensione-o.
2. O evento será atualizado automaticamente.

### Excluir um Evento

1. Clique em um evento existente no calendário.
2. Clique no botão "Excluir".
3. Confirme a exclusão.

- tutorial usado ->(https://www.youtube.com/watch?v=jFcDqLyUfPQ&t)
