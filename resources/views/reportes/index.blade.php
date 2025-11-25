@extends('adminlte::page')

@section('title', 'Generar Reportes')

@section('content_header')
    <h1>Generar Reportes de Recursos</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('reporte.recursos.pdf') }}" method="GET" target="_blank">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="report_type">Tipo de Reporte</label>
                        <select name="report_type" id="report_type" class="form-control">
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
                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date">Fecha de Fin</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}">
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
        if (this.value === 'day') {
            dayField.style.display = 'block';
            rangeFields.style.display = 'none';
        } else {
            dayField.style.display = 'none';
            rangeFields.style.display = 'block';
        }
    });
</script>
@stop