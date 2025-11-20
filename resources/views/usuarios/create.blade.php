@extends('adminlte::page')

@section('title', 'Nuevo Usuario')

@section('content_header')
    <h1>Crear Usuario</h1>
@stop

@section('content')
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

          
            <div class="form-group">
                <label for="dni">DNI</label>
                <div class="input-group">
                    <input type="text" name="dni" id="dni" class="form-control" placeholder="Ingrese DNI" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-success" id="buscarDni">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingrese nombre">
            </div>

            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" name="apellido" id="apellido" class="form-control" placeholder="Ingrese apellido">
            </div>

            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div id="emailAdvertencia" class="text-danger mt-1"></div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success" id="guardarBtn">Guardar</button>
            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const guardarBtn = document.getElementById('guardarBtn');

    document.getElementById('buscarDni').addEventListener('click', function () {
        const dni = document.getElementById('dni').value;
        fetch(`/personas/${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('nombre').value = data.first_name;
                    document.getElementById('apellido').value = data.last_name;

                    document.getElementById('nombre').readOnly = true;
                    document.getElementById('apellido').readOnly = true;

                    if (data.user) {
                        document.getElementById('email').value = data.user.email;
                        alert('Esta persona ya tiene un usuario registrado.');
                        guardarBtn.disabled = true; // Deshabilitar botón
                    } else {
                        document.getElementById('email').value = '';
                        guardarBtn.disabled = false; // Habilitar botón
                    }
                } else {
                    document.getElementById('nombre').value = '';
                    document.getElementById('apellido').value = '';
                    document.getElementById('email').value = '';

                    document.getElementById('nombre').readOnly = false;
                    document.getElementById('apellido').readOnly = false;

                    alert('Persona no encontrada. Podés cargarla manualmente.');
                    guardarBtn.disabled = false; // Habilitar botón
                }
            });
    });
});
</script>
@endsection