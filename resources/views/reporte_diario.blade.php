<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Reservas - {{ $fecha }}</h2>
    <table>
        <thead>
            <tr>
                <th>Recurso</th>
                <th>Usuario</th>
                <th>Hora</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservas as $reserva)
            <tr>
                <td>{{ $reserva->recurso->nombre }}</td>
                <td>{{ $reserva->usuario->nombre }}</td>
                <td>{{ $reserva->hora }}</td>
                <td>{{ $reserva->descripcion }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>