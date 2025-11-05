@extends('adminlte::page')

@section('title', 'Recursos')

@section('plugins.Datatables', true)

@section('content_header')
{{-- Se puede dejar vacío si usamos el título dentro del content --}}
@stop

@section('content')
<div class="container-fluid">

    {{-- Título y botón Nuevo Recurso --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center gap-4 mt-4">
                <h2 class="m-0">Nuevo recurso</h2>
   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNuevoRecurso">
    <i class="fas fa-plus me-1"></i> Nuevo Recurso
</button>

            </div>
        </div>
    </div>

    {{-- Alertas de sesión --}}
    @if(session('alert'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('alert') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="row mb-3">
        <div class="col-12">
            <form method="GET" action="{{ route('resources.index') }}" class="d-flex flex-wrap align-items-center">
                <input type="text" name="name" class="form-control  mr-2 mb-2" placeholder="Nombre"
                    value="{{ request('name') }}" style="width: 150px;">

                <input type="text" name="marca" class="form-control  mr-2 mb-2" placeholder="Marca"
                    value="{{ request('marca') }}" style="width: 120px;">

                <select name="status_resource_id" class="form-control  mr-2 mb-2" style="width: 120px;">
                    <option value="">-- Estado --</option>
                    @foreach (\App\Models\StatusResource::all() as $status)
                    <option value="{{ $status->id }}" {{ request('status_resource_id') == $status->id ? 'selected' : '' }}>
                        {{ $status->name }}
                    </option>
                    @endforeach
                </select>

                <select name="category_id" class="form-control  mr-2 mb-2" style="width: 120px;">
                    <option value="">-- Categoría --</option>
                    @foreach (\App\Models\Category::all() as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary  mb-2 d-flex align-items-center">
                    <i class="fas fa-search mr-1"></i> Filtrar
                </button>
            </form>

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
                    <table id="tabla-recursos" class="table table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Categoría</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($resources as $resource)
                            <tr>
                                <td>{{ $resource->id }}</td>
                                <td>{{ $resource->name }}</td>
                                <td>{{ $resource->marca }}</td>
                                <td>{{ Str::limit($resource->description, 80) }}</td>
                                <td>{{ $resource->status->name ?? '-' }}</td>
                                <td>{{ $resource->category->name ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <!-- Botón editar con icono -->
                                        <a href="{{ route('resources.edit', $resource) }}" class="btn btn-sm btn-warning text-white mr-2 mb-2" title="Editar">
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

                    {{-- Paginación --}}
                    <div class="mt-3">
                        {{ $resources->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Modal centrado -->
<div class="modal fade" id="modalNuevoRecurso" tabindex="-1" role="dialog" aria-labelledby="modalNuevoRecursoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form action="{{ route('resources.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuevoRecursoLabel">Nuevo Recurso</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- Campos del formulario -->
          <input type="text" name="name" class="form-control mb-2" placeholder="Nombre">
          <input type="text" name="marca" class="form-control mb-2" placeholder="Marca">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar Recurso</button>
        </div>
      </form>
    </div>
  </div>
</div>


</div>
@stop

@section('css')
@stop

@section('js')
@stop