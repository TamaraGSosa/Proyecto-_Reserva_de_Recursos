<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte por Rango de Fechas</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .section-title { margin-top: 20px; margin-bottom: 10px; background-color: #e6e6e6; padding: 5px; }
    </style>
</head>
<body>
    <h2>Reporte de Reservas y Recursos</h2>
    <h3>Desde: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} Hasta: {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</h3>

    <div class="section-title">
        <h3>Reservas en el Período</h3>
    </div>
    @if($reservations->isEmpty())
        <p>No se encontraron reservas en este período.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Recurso</th>
                    <th>Usuario</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->resource->name }}</td>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</td>
                    <td>{{ $reservation->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">
        <h3>Recursos Disponibles</h3>
    </div>
    @if($availableResources->isEmpty())
        <p>No hay recursos disponibles en este período.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availableResources as $resource)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $resource->name }}</td>
                    <td>{{ $resource->category->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="section-title">
        <h3>Recursos No Disponibles (Reservados)</h3>
    </div>
    @if($unavailableResources->isEmpty())
        <p>Todos los recursos están disponibles en este período.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unavailableResources as $resource)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $resource->name }}</td>
                    <td>{{ $resource->category->name ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
