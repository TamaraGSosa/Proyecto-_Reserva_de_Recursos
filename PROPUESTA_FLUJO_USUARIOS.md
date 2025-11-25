# Propuesta de Cambio en el Flujo de Creación de Usuarios

Actualmente, el administrador define la contraseña al crear el usuario. El objetivo es cambiar esto para que el administrador solo ingrese los datos básicos (DNI, Nombre, Email) y el sistema asigne una contraseña por defecto (el DNI), permitiendo que el usuario la cambie después.

A continuación, se detallan los pasos necesarios para implementar este cambio.

---

## 1. Modificar la Vista de Creación (`create.blade.php`)

Debemos eliminar los campos de "Contraseña" y "Confirmar Contraseña" del formulario que utiliza el administrador.

**Cambios:**
*   Eliminar el `input` de `password`.
*   Eliminar el `input` de `password_confirmation`.
*   Mantener el campo de DNI, Nombre, Apellido, Email y Rol.

## 2. Modificar el Controlador (`UserController.php`)

En el método `store` (donde se guarda el usuario), debemos asignar una contraseña automáticamente en lugar de recibirla del formulario.

**Lógica sugerida:**
*   Utilizar el **DNI** como contraseña inicial por defecto.
*   Encriptar esta contraseña antes de guardarla (`bcrypt($request->dni)`).

**Código conceptual:**
```php
$user = User::create([
    'name' => $request->nombre . ' ' . $request->apellido,
    'email' => $request->email,
    'password' => bcrypt($request->dni), // La contraseña inicial es el DNI
    'person_id' => $person->id,
]);
```

## 3. Permitir al Usuario Cambiar su Contraseña

Como el usuario entrará con su DNI como contraseña, necesita una forma de cambiarla por seguridad. Actualmente, el sistema no parece tener una vista dedicada de "Mi Perfil" o "Cambiar Contraseña".

**Pasos para implementar esto:**

1.  **Crear una nueva Ruta y Controlador:**
    *   Ruta: `/perfil/cambiar-password`
    *   Controlador: `ProfileController` (o agregar métodos en `UserController`).

2.  **Crear la Vista de Cambio de Contraseña:**
    *   Un formulario simple que pida:
        *   Contraseña Actual (para verificar).
        *   Nueva Contraseña.
        *   Confirmar Nueva Contraseña.

3.  **Lógica de Actualización:**
    *   Verificar que la "Contraseña Actual" coincida con la del usuario logueado.
    *   Actualizar la contraseña en la base de datos con la nueva.

## Resumen del Nuevo Flujo

1.  **Admin:** Entra a "Crear Usuario", ingresa DNI (ej: 12345678), Nombre y Email. Guarda.
2.  **Sistema:** Crea el usuario y le asigna la contraseña "12345678".
3.  **Usuario:** Ingresa al sistema con su Email y contraseña "12345678".
4.  **Usuario:** Va a la nueva opción "Cambiar Contraseña" y establece una segura.

---

¿Te gustaría que proceda a implementar estos cambios paso a paso?
