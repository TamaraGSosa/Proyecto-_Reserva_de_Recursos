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
                <h2 class="m-0 h5 h-md2" id="tituloSeccion">Nuevo recurso</h2>
                <button id="btnNuevoRecurso" type="button" class="btn btn-primary btn-sm btn-md mt-2 mt-md-0" data-toggle="modal" data-target="#modalNuevoRecurso">
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


    {{-- Formulario para Reporte PDF --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Reporte Diario de Recursos Reservados</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('reporte.recursos.pdf.dia') }}" method="GET" target="_blank" class="form-inline">
                        <div class="form-group mb-2">
                            <label for="fecha_recursos" class="sr-only">Fecha</label>
                            <input type="date" class="form-control" id="fecha_recursos" name="fecha" value="{{ date('Y-m-d') }}">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2 ml-2">Generar PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTROS --}}
    <div class="row mb-3">
        <div class="col-12 col-md-3 mb-2">
            <input type="text" id="filtro-nombre" class="form-control" placeholder="Buscar por nombre">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <input type="text" id="filtro-marca" class="form-control" placeholder="Buscar por marca">
        </div>
        <div class="col-12 col-md-3 mb-2">
            <select id="filtro-estado" class="form-control">
                <option value="">Todos los estados</option>
                @foreach ($status_resources as $est)
                <option value="{{ $est->name ?? $est }}">{{ $est->name ?? $est }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-3 mb-2">
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
                                {{-- Modal de eliminación específico para este recurso --}}
                                <x-adminlte-modal id="modalEliminar{{ $resource->id }}" title="Confirmar eliminación" size="md" icon="fas fa-exclamation-triangle" v-centered static-backdrop>
                                    <p>¿Estás seguro de que deseas eliminar el recurso <strong>{{ $resource->name }}- ID {{$resource->id}}</strong>?</p>
                                    <form method="POST" action="{{ route('resources.destroy', $resource) }}">
                                        @csrf
                                        @method('DELETE')
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
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
    <x-adminlte-modal id="modalNuevoRecurso" size="lg" icon="fas fa-plus" v-centered static-backdrop scrollable>


        <form id="formRecurso" method="POST" action="{{ route('resources.store') }}">
            @csrf
            <input type="hidden" name="_method" id="metodo" value="POST">
            <input type="hidden" name="resource_id" id="resource_id" value="">

            <div class="row">
                <div class="col-md-4 col-12 mb-2">
                    <x-adminlte-input name="name" id="name" label="Nombre" placeholder="Nombre" required />
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <x-adminlte-select name="status_resource_id" id="status_resource_id" label="Estado">
                        <x-adminlte-options :options="$status_resources->pluck('name','id')->toArray()" empty-option="Seleccione un estado" />
                    </x-adminlte-select>
                </div>
                <div class="col-md-4 col-12 mb-2">
                    <x-adminlte-select name="category_id" id="category_id" label="Categoría">
                        <x-adminlte-options :options="$categories->pluck('name','id')->toArray()" empty-option="Seleccione una categoría" />
                    </x-adminlte-select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-12 mb-2">
                    <x-adminlte-input name="marca" id="marca" label="Marca (Opcional)" placeholder="Marca" />
                </div>
                <div class="col-md-6 col-12 mb-2">
                    <x-adminlte-textarea name="description" id="description" label="Descripción (Opcional)" placeholder="Descripción" />
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Guardar Recurso</button>
            </div>
            
        </form>
        <x-slot name="footerSlot"> </x-slot>
    </x-adminlte-modal>
   


</div>
@stop

@section('css')
@stop

@section('js')
<script src="{{ asset('js/datatable.js') }}"></script>
<script>
    $(document).ready(function() {

        // Botón "Editar" en la tabla
        $('.btnEditar').click(function() {
            let resourceId = $(this).data('id');

            // Cambiar título del modal y acción del formulario
            $('#modalNuevoRecurso .modal-title').text('Editar Recurso');
            $('#formRecurso').attr('action', '/panel/resources/' + resourceId);
            $('#metodo').val('PUT');
            $('#resource_id').val(resourceId);

            // Llenar campos vía AJAX
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

        // Botón "Nuevo Recurso"
        $('#btnNuevoRecurso').click(function() {
            $('#modalNuevoRecurso .modal-title').text('Nuevo Recurso');
            $('#formRecurso').attr('action', '{{ route("resources.store") }}');
            $('#metodo').val('POST');
            $('#resource_id').val('');
            $('#formRecurso')[0].reset();
        });

        // Reset modal cuando se cierra
        $('#modalNuevoRecurso').on('hidden.bs.modal', function() {
            $('#modalNuevoRecurso .modal-title').text('Nuevo Recurso');
            $('#formRecurso').attr('action', '{{ route("resources.store") }}');
            $('#metodo').val('POST');
            $('#resource_id').val('');
            $('#formRecurso')[0].reset();
        });

    });
</script>
@stop