@extends('adminlte::page')

@section('title', 'Reporte de Recursos')

@section('content_header')
    <h1>Generar Reporte de Recursos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reporte.recursos.generar') }}" method="GET">
                <div class="form-group">
                    <label>Tipo de Reporte:</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_type" id="report_type_day" value="day" checked>
                        <label class="form-check-label" for="report_type_day">
                            Día Específico
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="report_type" id="report_type_range" value="range">
                        <label class="form-check-label" for="report_type_range">
                            Rango de Fechas
                        </label>
                    </div>
                </div>

                <div class="form-group" id="date_day_container">
                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', \Carbon\Carbon::today()->toDateString()) }}" required>
                </div>

                <div class="form-group" id="date_range_container" style="display: none;">
                    <div class="form-group">
                        <label for="start_date">Fecha de Inicio:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::today()->toDateString()) }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">Fecha de Fin:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', \Carbon\Carbon::today()->toDateString()) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportTypeDay = document.getElementById('report_type_day');
            const reportTypeRange = document.getElementById('report_type_range');
            const dateDayContainer = document.getElementById('date_day_container');
            const dateRangeContainer = document.getElementById('date_range_container');
            const fechaInput = document.getElementById('fecha');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            function toggleDateInputs() {
                if (reportTypeDay.checked) {
                    dateDayContainer.style.display = 'block';
                    dateRangeContainer.style.display = 'none';
                    fechaInput.setAttribute('required', 'required');
                    startDateInput.removeAttribute('required');
                    endDateInput.removeAttribute('required');
                } else {
                    dateDayContainer.style.display = 'none';
                    dateRangeContainer.style.display = 'block';
                    fechaInput.removeAttribute('required');
                    startDateInput.setAttribute('required', 'required');
                    endDateInput.setAttribute('required', 'required');
                }
            }

            reportTypeDay.addEventListener('change', toggleDateInputs);
            reportTypeRange.addEventListener('change', toggleDateInputs);

            // Initial call to set the correct state based on default checked radio
            toggleDateInputs();
        });
    </script>
@stop
