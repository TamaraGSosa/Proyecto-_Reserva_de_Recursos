@extends('adminlte::page')

@section('title', 'Generar Reportes de Reservaciones')

@section('content_header')
    <h1>Generar Reportes de Reservaciones</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {{-- La acción del formulario debe apuntar a la ruta de reportes de reservaciones --}}
        <form action="{{ route('reportes.reservas.pdf') }}" method="GET" target="_blank">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="report_type">Tipo de Reporte</label>
                        <select name="tipo_de_informe" id="report_type" class="form-control">
                            <option value="day">Diario</option>
                            <option value="range">Por Rango de Fechas</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4" id="day_field">
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-4" id="range_fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date">Fecha de Inicio</label>
                                <input type="date" name="fecha_de_inicio" id="start_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">Fecha de Fin</label>
                                <input type="date" name="fecha_final" id="end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-file-pdf"></i> Generar PDF
            </button>
        </form>
    </div>
</div>
@stop

@section('js')
<script>
    document.getElementById('report_type').addEventListener('change', function () {
        var dayField = document.getElementById('day_field');
        var rangeFields = document.getElementById('range_fields');
        var fechaInput = document.getElementById('fecha');
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');

        if (this.value === 'day') {
            dayField.style.display = 'block';
            fechaInput.disabled = false;
            rangeFields.style.display = 'none';
            startDateInput.disabled = true;
            endDateInput.disabled = true;
        } else {
            dayField.style.display = 'none';
            fechaInput.disabled = true;
            rangeFields.style.display = 'block';
            startDateInput.disabled = false;
            endDateInput.disabled = false;
        }
    });

    // Inicializar el estado de los campos al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        var reportType = document.getElementById('report_type');
        var dayField = document.getElementById('day_field');
        var rangeFields = document.getElementById('range_fields');
        var fechaInput = document.getElementById('fecha');
        var startDateInput = document.getElementById('start_date');
        var endDateInput = document.getElementById('end_date');

        if (reportType.value === 'day') {
            dayField.style.display = 'block';
            fechaInput.disabled = false;
            rangeFields.style.display = 'none';
            startDateInput.disabled = true;
            endDateInput.disabled = true;
        } else {
            dayField.style.display = 'none';
            fechaInput.disabled = true;
            rangeFields.style.display = 'block';
            startDateInput.disabled = false;
            endDateInput.disabled = false;
        }
    });
</script>
@stop