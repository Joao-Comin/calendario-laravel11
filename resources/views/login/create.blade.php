@extends('layouts.auth')

@section('content')
    <main class="form-signin w-100 m-auto text-center bg-light rounded">
        
        <h1 class="h3 mb-3 fw-normal">Novo Usuário</h1>

        

        <form action="{{ route('login.store-user') }}" method="POST">
            @csrf
            @method('POST')

            <div class="form-floating mb-4">
                <input type="text" name="name" class="form-control" id="name"
                    placeholder="Digite o nome completo" value="{{ old('name') }}">
                <label for="name">Nome</label>
            </div>

            <div class="form-floating mb-4">
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="Digite o seu melhor e-mail" value="{{ old('email') }}">
                <label for="email">E-mail</label>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <!-- Campo flutuante -->
                    <div class="form-floating flex-grow-1">
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Senha com no mínimo 6 caracteres">
                        <label for="password">Senha</label>
                    </div>
                    <!-- Ícone -->
                    <span class="input-group-text" role="button" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <!-- Campo flutuante -->
                    <div class="form-floating flex-grow-1">
                        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation"
                            placeholder="Confirma a senha">
                        <label for="password_confirmation">Confirma Senha</label>
                    </div>
                    <!-- Ícone -->
                    <span class="input-group-text" role="button" onclick="togglePassword('password_confirmation', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <button class="btn btn-primary w-100 py-2 mb-4" type="submit">Cadastrar</button>

            <a href="{{ route('login') }}" class="text-decoration-none">Login</a>
        </form>
    </main>
@endsection
