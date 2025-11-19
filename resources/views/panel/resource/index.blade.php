@extends('adminlte::page')

@section('title', 'Recursos')

@section('plugins.Datatables', true)
@section('plugins.DataTablesResponsive', true)

@section('content_header')
@stop

@section('content')
<div class="container-fluid">
    {{-- Título y botón Nuevo Recurso --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap mt-4">
                <button id="btnNuevoRecurso"
                    type="button"
                    class="btn btn-primary btn-sm btn-md mt-2 mt-md-0"
                    data-toggle="modal"
                    data-target="#modalNuevoRecurso"
                    title="Agregar un nuevo recurso">
                    <i class="fas fa-plus mr-1"></i> Nuevo Recurso
                </button>
            </div>
        </div>
    </div>

    {{-- Alertas de sesión --}}
    @if(session('success'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- FILTROS --}}
    <div class="row mb-3">
        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-nombre" class="form-label">Nombre</label>
            <input type="text" id="filtro-nombre" class="form-control" placeholder="Buscar por nombre">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-marca" class="form-label">Marca</label>
            <input type="text" id="filtro-marca" class="form-control" placeholder="Buscar por marca">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-estado" class="form-label">Estado</label>
            <select id="filtro-estado" class="form-control">
                <option value="">Todos los estados</option>
                @foreach ($status_resources as $est)
                <option value="{{ $est->name ?? $est }}">{{ $est->name ?? $est }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 mb-2">
            <label for="filtro-categoria" class="form-label">Categoría</label>
            <select id="filtro-categoria" class="form-control">
                <option value="">Todas las categorías</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Tabla de recursos --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if ($resources->isEmpty())
                    <p class="alert alert-danger text-center">No hay recursos cargados</p>
                    @else
                    <div class="table-responsive">
                        <table id="tabla-dt" class="table table-striped table-hover w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Estado</th>
                                    <th>Categoría</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resources as $resource)
                                <tr>
                                    <td>{{ $resource->id }}</td>
                                    <td>{{ Str::limit($resource->name,20) }}</td>
                                    <td>{{ Str::limit($resource->marca,20) }}</td>
                                    <td>{{ $resource->status->name ?? '-' }}</td>
                                    <td>{{ $resource->category->name ?? '-' }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-sm btn-primary btnEditar mr-2 mb-2" data-id="{{ $resource->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>


                                            <button class="btn btn-sm btn-danger mr-2 mb-2 " data-toggle="modal" data-target="#modalEliminar{{ $resource->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                                {{-- Modal de eliminación irreversible --}}
                                <x-adminlte-modal id="modalEliminar{{ $resource->id }}" title="¡Eliminar recurso!" size="md" icon="fas fa-exclamation-triangle" v-centered static-backdrop>
                                    <p>¿Estás seguro de que deseas <strong>eliminar permanentemente</strong> el recurso <strong>{{ $resource->name }} - ID {{$resource->id}}</strong>?</p>
                                    <p class="text-danger">⚠ Esta acción <strong>no se puede deshacer</strong> y el recurso dejará de estar disponible en el sistema.</p>
                                    <form method="POST" action="{{ route('resources.destroy', $resource) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-danger">Eliminar definitivamente</button>
                                        </div>
                                    </form>
                                    <x-slot name="footerSlot"> </x-slot>
                                </x-adminlte-modal>

                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Modal Nuevo/Editar Recurso --}}
    <div class="modal fade" id="modalNuevoRecurso" tabindex="-1" role="dialog" aria-labelledby="modalNuevoRecursoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoRecursoLabel">Nuevo Recurso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="formRecurso" method="POST" action="{{ route('resources.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="metodo" value="POST">
                    <input type="hidden" name="resource_id" id="resource_id" value="">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 col-12 mb-2">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                                <span class="text-danger" id="error-name">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="col-md-4 col-12 mb-2">
                                <label for="status_resource_id">Estado</label>
                                <select class="form-control" name="status_resource_id" id="status_resource_id">
                                    <option value="">Seleccione un estado</option>
                                    @foreach($status_resources as $status)
                                    <option value="{{ $status->id }}" {{ old('status_resource_id') == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error-status">{{ $errors->first('status_resource_id') }}</span>
                            </div>

                            <div class="col-md-4 col-12 mb-2">
                                <label for="category_id">Categoría</label>
                                <select class="form-control" name="category_id" id="category_id">
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="error-category">{{ $errors->first('category_id') }}</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <label for="marca">Marca (Opcional)</label>
                                <input type="text" class="form-control" name="marca" id="marca" value="{{ old('marca') }}">
                                <span class="text-danger" id="error-marca">{{ $errors->first('marca') }}</span>
                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <label for="description">Descripción (Opcional)</label>
                                <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                                <span class="text-danger" id="error-description">{{ $errors->first('description') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Guardar Recurso</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Script para abrir el modal automáticamente si hay errores --}}
    @if ($errors->any())
    <script>
        $(document).ready(function() {
            $('#modalNuevoRecurso').modal('show');
        });
    </script>
    @endif



</div>
@stop

@section('css')

@stop


@section('js')
<script src="{{ asset('js/datatable.js') }}"></script>
{{-- Script independiente para abrir modal si hay errores --}}
@if ($errors->any())
<script>
    // Abrir el modal inmediatamente al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        $('#modalNuevoRecurso').modal('show');
    });
</script>
@endif

<script>
    $(document).ready(function() {

        // Reset modal
        function resetModal() {
            $('#formRecurso')[0].reset();
            $('#formRecurso .is-invalid').removeClass('is-invalid');
            $('#formRecurso .text-danger').text('');
            $('#modalNuevoRecurso .modal-title').text('Nuevo Recurso');
            $('#formRecurso').attr('action', '{{ route("resources.store") }}');
            $('#metodo').val('POST');
            $('#resource_id').val('');
        }

        // Abrir modal para nuevo
        $('#btnNuevoRecurso').click(function() {
            resetModal();
            $('#modalNuevoRecurso').modal('show');
        });

        $(document).on('click', '.btnEditar', function() {
            let resourceId = $(this).data('id');
            resetModal();

            $('#modalNuevoRecurso .modal-title').text('Editar Recurso');
            $('#formRecurso').attr('action', '/panel/resources/' + resourceId);
            $('#metodo').val('PUT');
            $('#resource_id').val(resourceId);

            $.get('/panel/resources/' + resourceId + '/json', function(data) {
                $('#name').val(data.name);
                $('#marca').val(data.marca);
                $('#description').val(data.description);
                $('#status_resource_id').val(data.status_resource_id).change();
                $('#category_id').val(data.category_id).change();

                $('#modalNuevoRecurso').modal('show');
            }).fail(function() {
                alert('Error al cargar los datos del recurso');
            });
        });

        // Foco al primer input
        $('#modalNuevoRecurso').on('shown.bs.modal', function() {
            $('#name').focus();
        });

        // Reset al cerrar
        $('#modalNuevoRecurso').on('hidden.bs.modal', function() {
            resetModal();
        });
    });
</script>
@stop