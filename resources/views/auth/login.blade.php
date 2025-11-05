@extends('layouts.app')

@section('title', 'Iniciar Sesión')
@section('body_class', 'hold-transition login-page')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <img src="{{ asset('images/logo.png') }}" alt="logo.png" style="max-width:150px;">
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Inicia sesión</p>

            <form action="{{ route('login') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append">
                        <div class="input-group-text"><span class="fas fa-lock"></span></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Recordarme</label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </div>
                </div>
            </form>

            <p class="mb-1">
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            </p>
        </div>
    </div>
</div>
@endsection
