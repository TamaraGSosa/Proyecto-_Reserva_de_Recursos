<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Registro</title>
</head>
<body>
    <p>Hola {{ $user->name }},</p>

    <p>Tu cuenta ha sido creada con éxito en nuestro sistema.</p>

    <ul>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Fecha de registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Ahora puedes acceder al sistema con tus credenciales.</p>

    <p>Gracias por registrarte.</p>
</body>
</html>