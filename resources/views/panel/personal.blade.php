@extends('adminlte::page')

@section('title', 'Dashboard Personal')

@section('content_header')
    <h1>Dashboard Personal de Recursos</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- Tarjetas de estadísticas personales --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $misReservasDelDia }}</h3>
                    <p class="card-text">Mis reservas del día</p>
                    <i class="fas fa-calendar-day fa-2x"></i>
                    <br>
                    <button class="btn btn-light btn-sm mt-2" data-toggle="modal" data-target="#modalReservasDia">
                        <i class="fas fa-eye"></i> Ver detalles
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $misReservasNoEntregadas }}</h3>
                    <p class="card-text">Mis no entregadas</p>
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                    <br>
                    <button class="btn btn-light btn-sm mt-2" data-toggle="modal" data-target="#modalNoEntregadas">
                        <i class="fas fa-eye"></i> Ver detalles
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $categoriesWithResources->sum('available_resources') }}</h3>
                    <p class="card-text">Recursos disponibles</p>
                    <i class="fas fa-check-circle fa-2x"></i>
                    <br>
                    <button class="btn btn-light btn-sm mt-2" data-toggle="modal" data-target="#modalRecursosDisponibles">
                        <i class="fas fa-eye"></i> Ver detalles
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Mis Reservas del Día --}}
    <x-adminlte-modal id="modalReservasDia" size="lg" icon="fas fa-calendar-day" v-centered static-backdrop scrollable>
        <h5>Mis Reservas del Día ({{ \Carbon\Carbon::now()->format('d/m/Y') }})</h5>
        @if($misReservasDelDiaDetalles->isEmpty())
            <p class="text-muted">No tienes reservas programadas para hoy.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Recursos</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($misReservasDelDiaDetalles as $reserva)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}</td>
                            <td>
                                @foreach($reserva->resources as $resource)
                                <span class="badge badge-secondary">{{ $resource->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-{{ $reserva->status->name === 'En curso' ? 'success' : ($reserva->status->name === 'Pendiente' ? 'warning' : 'secondary') }}">
                                    {{ $reserva->status->name }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <x-slot name="footerSlot">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </x-slot>
    </x-adminlte-modal>

    {{-- Modal Mis No Entregadas --}}
    <x-adminlte-modal id="modalNoEntregadas" size="lg" icon="fas fa-exclamation-triangle" v-centered static-backdrop scrollable>
        <h5>Mis Reservas No Entregadas</h5>
        @if($misReservasNoEntregadasDetalles->isEmpty())
            <p class="text-success">¡Excelente! Todas tus reservas han sido entregadas correctamente.</p>
        @else
            <div class="alert alert-warning">
                <strong>Atención:</strong> Estas reservas deberían haber sido entregadas pero aún no lo han sido.
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Recursos</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($misReservasNoEntregadasDetalles as $reserva)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m H:i') }} -
                                {{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}
                            </td>
                            <td>
                                @foreach($reserva->resources as $resource)
                                <span class="badge badge-secondary">{{ $resource->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-danger">{{ $reserva->status->name }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <x-slot name="footerSlot">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </x-slot>
    </x-adminlte-modal>

    {{-- Modal Recursos Disponibles --}}
    <x-adminlte-modal id="modalRecursosDisponibles" size="lg" icon="fas fa-check-circle" v-centered static-backdrop scrollable>
        <h5>Recursos Disponibles por Categoría</h5>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>Disponibles</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categoriesWithResources as $category)
                    <tr>
                        <td><i class="fas fa-cubes"></i> {{ $category['name'] }}</td>
                        <td><span class="badge badge-success">{{ $category['available_resources'] }}</span></td>
                        <td>{{ $category['total_resources'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <x-slot name="footerSlot">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </x-slot>
    </x-adminlte-modal>
</div>
@stop

@section('css')
<style>
.card-header small {
    font-size: 0.8em;
}
.border {
    border-color: #dee2e6 !important;
}
</style>
@stop