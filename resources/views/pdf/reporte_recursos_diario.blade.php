<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Diario de Recursos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Recursos Reservados - {{ $fecha }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Categoría</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recursos as $recurso)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $recurso->name }}</td>
                <td>{{ $recurso->description }}</td>
                <td>{{ $recurso->category->name ?? 'Sin categoría' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">No se encontraron recursos reservados para esta fecha.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
