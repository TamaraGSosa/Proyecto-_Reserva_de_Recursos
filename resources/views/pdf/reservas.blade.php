<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'Reporte de Reservas' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Recurso</th>
                <th>Fecha de Reserva</th>
                <th>Hora de Inicio</th>
                <th>Hora de Fin</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $reserva)
                <tr>
                    <td>{{ $reserva->id }}</td>
                    <td>{{ $reserva->profile->person->first_name ?? 'N/A' }} {{ $reserva->profile->person->last_name ?? '' }}</td>
                    <td>
                        @if($reserva->resources->isNotEmpty())
                            @foreach($reserva->resources as $resource)
                                {{ $resource->name }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            Recurso no especificado
                        @endif
                    </td>
                    <td>{{ \Carbon\Carbon::parse($reserva->start_time)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->start_time)->format('H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->end_time)->format('H:i') }}</td>
                    <td>{{ $reserva->status->name ?? 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No se encontraron reservas para el período seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
