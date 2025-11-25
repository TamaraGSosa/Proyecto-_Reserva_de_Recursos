# Guía: Agregar "Cambiar Contraseña" al Menú Lateral

Para que aparezca la opción de "Cambiar Contraseña" en el panel izquierdo (sidebar), debes editar el archivo de configuración de AdminLTE.

## Pasos a seguir

1.  Abre el archivo `config/adminlte.php`.
2.  Busca la sección `'menu' => [ ... ]` (aproximadamente en la línea 303).
3.  Agrega el siguiente bloque de código dentro del array `menu`, donde quieras que aparezca (por ejemplo, al final de la lista):

```php
        [
            'header' => 'AJUSTES DE CUENTA',
        ],
        [
            'text' => 'Cambiar Contraseña',
            'route' => 'profile.password.edit',
            'icon' => 'fas fa-fw fa-lock',
        ],
```

## Ejemplo completo de cómo quedaría

Tu sección `menu` debería verse algo así después del cambio:

```php
    'menu' => [
        // ... otros items ...

        [
            'text' => 'Gestión de Usuarios',
            'route' => 'usuarios.index',
            'icon'=>'fas fa-users',
            'can' => 'gestionar_usuarios',
        ],

        // NUEVO BLOQUE AGREGADO
        [
            'header' => 'AJUSTES DE CUENTA',
        ],
        [
            'text' => 'Cambiar Contraseña',
            'route' => 'profile.password.edit',
            'icon' => 'fas fa-fw fa-lock',
        ],
    ],
```

## Explicación
*   `header`: Crea un pequeño título separador en el menú.
*   `text`: El texto que verá el usuario.
*   `route`: El nombre de la ruta que creamos (`profile.password.edit`).
*   `icon`: El icono de FontAwesome (un candado en este caso).

¡Guarda el archivo y recarga la página para ver los cambios!
