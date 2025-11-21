@extends('adminlte::page')

@section('title', 'Reporte por Rango de Fechas')

@section('content_header')
    <h1>Generar Reporte por Rango de Fechas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reporte.pdf.rango') }}" method="GET">
                <div class="form-group">
                    <label for="start_date">Fecha de Inicio:</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', \Carbon\Carbon::today()->toDateString()) }}" required>
                </div>
                <div class="form-group">
                    <label for="end_date">Fecha de Fin:</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', \Carbon\Carbon::today()->toDateString()) }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Generar Reporte</button>
            </form>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
