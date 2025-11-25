@extends('adminlte::page')

@section('title', 'Gestión de Recursos')

@section('content_header')
    <h1>
        <i class="fas fa-cubes"></i> Gestión de Recursos
    </h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-list"></i> Listado de Recursos
        </h3>
        <div class="card-tools">
            <!-- Botones de exportación -->
            <a href="{{ route('recursos.pdf.visualizar') }}" class="btn btn-sm btn-primary" target="_blank">
                <i class="fas fa-eye"></i> Visualizar PDF
            </a>
            <a href="{{ route('recursos.pdf.descargar') }}" class="btn btn-sm btn-danger">
                <i class="fas fa-file-pdf"></i> Descargar PDF
            </a>
            <button type="button" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Nuevo Recurso
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 20%;">Nombre</th>
                    <th style="width: 12%;">Tipo</th>
                    <th style="width: 10%;">Capacidad</th>
                    <th style="width: 25%;">Ubicación</th>
                    <th style="width: 13%;">Estado</th>
                    <th style="width: 15%;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recursos as $recurso)
                <tr>
                    <td>{{ $recurso['id'] }}</td>
                    <td>{{ $recurso['nombre'] }}</td>
                    <td>{{ $recurso['tipo'] }}</td>
                    <td class="text-center">{{ $recurso['capacidad'] }}</td>
                    <td>{{ $recurso['ubicacion'] }}</td>
                    <td>
                        @if($recurso['estado'] == 'Disponible')
                            <span class="badge badge-success">{{ $recurso['estado'] }}</span>
                        @elseif($recurso['estado'] == 'Mantenimiento')
                            <span class="badge badge-warning">{{ $recurso['estado'] }}</span>
                        @else
                            <span class="badge badge-danger">{{ $recurso['estado'] }}</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-info" title="Ver">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No hay recursos registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <small class="text-muted">Total de recursos: {{ count($recursos) }}</small>
    </div>
</div>
@stop

@section('css')
    <style>
        .card-tools .btn {
            margin-left: 5px;
        }
    </style>
@stop