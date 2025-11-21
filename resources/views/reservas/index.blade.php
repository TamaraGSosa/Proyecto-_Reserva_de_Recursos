@extends('adminlte::page')

@section('title', 'Gestión de Reservas')

@section('content_header')
    <h1>Gestión de Reservas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtrar Reservas</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('reservations.index') }}" method="GET">
                <div class="form-group">
                    <label>Tipo de Filtro:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filter_type" id="filter_type_day" value="day" {{ request('filter_type', 'day') == 'day' ? 'checked' : '' }}>
                        <label class="form-check-label" for="filter_type_day">
                            Día Específico
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="filter_type" id="filter_type_range" value="range" {{ request('filter_type') == 'range' ? 'checked' : '' }}>
                        <label class="form-check-label" for="filter_type_range">
                            Rango de Fechas
                        </label>
                    </div>
                </div>

                <div class="form-group" id="filter_day_container">
                    <label for="filter_fecha">Fecha:</label>
                    <input type="date" name="filter_fecha" id="filter_fecha" class="form-control" value="{{ request('filter_fecha', \Carbon\Carbon::today()->toDateString()) }}">
                </div>

                <div class="form-group" id="filter_range_container" style="display: none;">
                    <div class="form-group">
                        <label for="filter_start_date">Fecha de Inicio:</label>
                        <input type="date" name="filter_start_date" id="filter_start_date" class="form-control" value="{{ request('filter_start_date', \Carbon\Carbon::today()->toDateString()) }}">
                    </div>
                    <div class="form-group">
                        <label for="filter_end_date">Fecha de Fin:</label>
                        <input type="date" name="filter_end_date" id="filter_end_date" class="form-control" value="{{ request('filter_end_date', \Carbon\Carbon::today()->toDateString()) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
            </form>
        </div>
    </div>

    {{-- Aquí iría el contenido original de la tabla de reservas --}}
    {{-- El fragmento que me proporcionaste: --}}
    <form action="{{ route('pdf.reservas-dia') }}" method="GET" class="d-flex align-items-center mb-3">
        <input type="date" name="fecha" class="form-control me-2" required>
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Descargar PDF del Día
        </button>
    </form>

    {{-- Asumo que la tabla de reservas se renderiza aquí o se carga via JS --}}
    {{-- Si la tabla de reservas no aparece, necesitaré más contexto sobre cómo se carga. --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Listado de Reservas</h3>
        </div>
        <div class="card-body">
            {{-- Aquí es donde se esperaría la tabla de reservas. --}}
            {{-- Por ahora, dejaré un placeholder. --}}
            <p>Contenido de la tabla de reservas (se cargará aquí).</p>
            {{-- Si usas DataTables, el script para inicializarlo iría en @section('js') --}}
        </div>
    </div>

@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterTypeDay = document.getElementById('filter_type_day');
            const filterTypeRange = document.getElementById('filter_type_range');
            const filterDayContainer = document.getElementById('filter_day_container');
            const filterRangeContainer = document.getElementById('filter_range_container');
            const filterFechaInput = document.getElementById('filter_fecha');
            const filterStartDateInput = document.getElementById('filter_start_date');
            const filterEndDateInput = document.getElementById('filter_end_date');

            function toggleFilterInputs() {
                if (filterTypeDay.checked) {
                    filterDayContainer.style.display = 'block';
                    filterRangeContainer.style.display = 'none';
                    filterFechaInput.setAttribute('required', 'required');
                    filterStartDateInput.removeAttribute('required');
                    filterEndDateInput.removeAttribute('required');
                } else {
                    filterDayContainer.style.display = 'none';
                    filterRangeContainer.style.display = 'block';
                    filterFechaInput.removeAttribute('required');
                    filterStartDateInput.setAttribute('required', 'required');
                    filterEndDateInput.setAttribute('required', 'required');
                }
            }

            filterTypeDay.addEventListener('change', toggleFilterInputs);
            filterTypeRange.addEventListener('change', toggleFilterInputs);

            // Initial call to set the correct state based on default checked radio or request
            toggleFilterInputs();
        });
    </script>
@stop
