@extends('layouts.auth')

@section('content')
    <main class="form-signin w-100 m-auto text-center bg-light rounded">

        <h1 class="h3 mb-3 fw-normal">Área Restrita</h1>

        <form action="{{ route('login.process') }}" method="POST">
            @csrf
            @method('POST')

            <div class="form-floating mb-4">
                <input type="email" name="email" class="form-control" id="email"
                    placeholder="Digite o e-mail de usuário" value="{{ old('email') }}">
                <label for="email">Usuário</label>
            </div>

            <div class="mb-4">
                <div class="input-group">
                    <div class="form-floating">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        <label for="password">Senha</label>
                    </div>
                    <span class="input-group-text" role="button" onclick="togglePassword('password', this)"><i
                            class="bi bi-eye"></i></span>
                </div>
            </div>

            <button class="btn btn-primary w-100 py-2 mb-4" type="submit">Acessar</button>

            <a href="" class="text-decoration-none">Cadastrar</a>
            
        </form>
    </main>
@endsection
