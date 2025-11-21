@extends('adminlte::page')

@section('title', 'Reporte Diario')

@section('content_header')
    <h1>Generar Reporte Diario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('reporte.pdf.dia') }}" method="GET">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', \Carbon\Carbon::today()->toDateString()) }}" required>
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
