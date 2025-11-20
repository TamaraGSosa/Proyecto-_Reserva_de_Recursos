<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Recursos</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .available { color: green; font-weight: bold; }
        .unavailable { color: red; font-weight: bold; }
        h2 { text-align: center; }
        .date-info { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>Reporte de Recursos</h2>
    <div class="date-info">
        @if ($reportType === 'day')
            <p>Fecha: {{ $startDate->format('d/m/Y') }}</p>
        @else
            <p>Rango de Fechas: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
        @endif
    </div>

    <h3>Recursos Disponibles</h3>
    @if ($availableResources->isEmpty())
        <p>No hay recursos disponibles en el período seleccionado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($availableResources as $resource)
                <tr>
                    <td>{{ $resource->name }}</td>
                    <td>{{ $resource->category->name }}</td>
                    <td class="available">Disponible</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h3>Recursos No Disponibles</h3>
    @if ($unavailableResources->isEmpty())
        <p>No hay recursos no disponibles en el período seleccionado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unavailableResources as $resource)
                <tr>
                    <td>{{ $resource->name }}</td>
                    <td>{{ $resource->category->name }}</td>
                    <td class="unavailable">No Disponible</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>