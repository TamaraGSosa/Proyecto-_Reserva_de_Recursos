<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Reserva</title>
</head>
<body>
    <p>Hola {{ $data['nombre'] }},</p>

    <p>Tu reserva ha sido confirmada con éxito.</p>

    <ul>
        <li><strong>Recursos reservados:</strong> {{ $data['producto'] }}</li>
        <li><strong>Fecha de reserva:</strong> {{ $data['fecha'] }}</li>
    </ul>

    <p>Gracias por usar nuestro sistema.</p>
</body>
</html>
