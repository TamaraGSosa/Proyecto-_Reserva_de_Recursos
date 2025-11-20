<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            font-size: 12px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #7f8c8d;
        }
        .section-title {
            font-size: 18px;
            color: #2980b9;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #34495e;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .summary-table th {
            width: 50%;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #95a5a6;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reporte Diario de Actividad</h1>
            <p>Fecha de generación: {{ $fecha }}</p>
        </div>

        <h2 class="section-title">Gestión de Recursos</h2>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Cantidad</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total de Recursos Disponibles</td>
                    <td>{{ $totalRecursos }}</td>
                </tr>
                <tr>
                    <td>Recursos Reservados en el Día</td>
                    <td>{{ $recursosReservadosHoy }}</td>
                </tr>
            </tbody>
        </table>

        <h2 class="section-title">Detalle de Reservas del Día</h2>
        @if($reservas->isEmpty())
            <p>No se encontraron reservas para el día de hoy.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>ID Reserva</th>
                        <th>Recurso Reservado</th>
                        <th>Usuario</th>
                        <th>Fecha de Reserva</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->id }}</td>
                            <td>{{ $reserva->resource->resource_name ?? 'N/A' }}</td>
                            <td>{{ $reserva->user->name ?? 'N/A' }}</td>
                            <td>{{ $reserva->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $reserva->statusReservation->status_name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="footer">
            <p>Proyecto - Reserva de Recursos</p>
        </div>
    </div>
</body>
</html>
