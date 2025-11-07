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
                <!-- Título: tamaño responsive -->
                <h2 class="m-0 h5 h-md2">Nuevo recurso</h2>

                <!-- Botón: tamaño responsive usando btn-sm en móviles y btn-md en desktop -->
                <button type="button" class="btn btn-primary btn-sm btn-md mt-2 mt-md-0" data-toggle="modal" data-target="#modalNuevoRecurso">
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


    <!-- 🔍 FILTROS -->
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
                                            <!-- Botón editar con icono -->
                                            <a href="{{ route('resources.edit', $resource) }}" class="btn btn-sm btn-primary text-white mr-2 mb-2" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Botón eliminar con icono -->
                                            <form action="{{ route('resources.destroy', $resource) }}" method="POST" onsubmit="return confirm('¿Eliminar recurso?')" class="mr-2 mb-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <x-adminlte-modal id="modalNuevoRecurso" title="Nuevo Recurso" size="lg" icon="fas fa-plus" v-centered static-backdrop scrollable>
        <form action="{{ route('resources.store') }}" method="POST">
            @csrf

            {{-- Primera fila --}}
            <div class="row">
                <div class="col-md-4 col-12 mb-2">
                    <x-adminlte-input name="name" label="Nombre" placeholder="Nombre" required />
                </div>

                <div class="col-md-4 col-12 mb-2">
                    <x-adminlte-select name="status_resource_id" label="Estado">
                        <x-adminlte-options :options="$status_resources->pluck('name','id')->toArray()" empty-option="Seleccione un estado" />
                    </x-adminlte-select>
                </div>

                <div class="col-md-4 col-12 mb-2">
                    <x-adminlte-select name="category_id" label="Categoría">
                        <x-adminlte-options :options="$categories->pluck('name','id')->toArray()" empty-option="Seleccione una categoría" />
                    </x-adminlte-select>
                </div>
            </div>

            {{-- Segunda fila --}}
            <div class="row">
                <div class="col-md-6 col-12 mb-2">
                    <x-adminlte-input name="marca" label="Marca (Opcional)" placeholder="Marca" />
                </div>

                <div class="col-md-6 col-12 mb-2">
                    <x-adminlte-textarea name="description" label="Descripción (Opcional)" placeholder="Descripción" />
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="submit" class="btn btn-primary">Guardar Recurso</button>
            </div>
            <x-slot name="footerSlot">

            </x-slot>
        </form>
    </x-adminlte-modal>

</div>


</div>
@stop

@section('css')
@stop

@section('js')
<script src="{{ asset('js/datatable.js') }}"></script>
@stop