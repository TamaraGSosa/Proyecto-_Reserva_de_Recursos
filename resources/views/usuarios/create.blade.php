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
        <div id="dniAlert" class="alert alert-danger d-none"></div>
        <form action="{{ route('usuarios.store') }}" method="POST" autocomplete="off">
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
                <input type="email" name="email" id="email" class="form-control" required autocomplete="off">
            </div>
            <div id="emailAdvertencia" class="text-danger mt-1"></div>

          
            <div class="form-group">
                <label for="role">Rol</label>
                <select name="role" id="role" class="form-control" required>
                    <option value="" disabled selected>Seleccione un rol</option>
                    <option value="administrador">Administrador</option>
                    <option value="personal">Personal</option>
                </select>
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

    const dniInput = document.getElementById('dni');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const emailInput = document.getElementById('email');
    const alertBox = document.getElementById('dniAlert');

    // Limpiar campos y alertas al escribir un nuevo DNI
    dniInput.addEventListener('input', function() {
        alertBox.classList.add('d-none');
        nombreInput.value = '';
        apellidoInput.value = '';
        emailInput.value = '';
        nombreInput.readOnly = false;
        apellidoInput.readOnly = false;
        guardarBtn.disabled = false;
    });

    document.getElementById('buscarDni').addEventListener('click', function () {
        alertBox.classList.add('d-none');
        const dni = dniInput.value;
        fetch(`/personas/${dni}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    nombreInput.value = data.first_name;
                    apellidoInput.value = data.last_name;

                    nombreInput.readOnly = true;
                    apellidoInput.readOnly = true;

                    if (data.user) {
                        emailInput.value = data.user.email;
                        alertBox.textContent = 'Esta persona ya tiene un usuario registrado.';
                        alertBox.classList.remove('d-none');
                        guardarBtn.disabled = true; // Deshabilitar botón
                    } else {
                        emailInput.value = '';
                        guardarBtn.disabled = false; // Habilitar botón
                    }
                } else {
                    // Si no se encuentra, permitimos cargar manualmente sin alerta intrusiva
                    nombreInput.value = '';
                    apellidoInput.value = '';
                    emailInput.value = '';

                    nombreInput.readOnly = false;
                    apellidoInput.readOnly = false;
                    guardarBtn.disabled = false; // Habilitar botón
                }
            });
    });
});
</script>
@endsection