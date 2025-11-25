# Informe Técnico: Sistema de Reserva de Recursos

## 1. Introducción
Este documento presenta un análisis técnico detallado del "Sistema de Reserva de Recursos". El objetivo es proporcionar una visión clara y "humanizada" de cómo funciona el sistema por dentro, destacando las tecnologías utilizadas, la estructura de datos y, fundamentalmente, las reglas de negocio y validaciones que aseguran la integridad de la información.

## 2. Arquitectura y Tecnologías
El sistema está construido sobre una base sólida y moderna, utilizando tecnologías probadas en la industria:

*   **Framework Backend**: Laravel (PHP). Se utiliza su arquitectura MVC (Modelo-Vista-Controlador) para separar la lógica de negocio, la interfaz de usuario y el manejo de datos.
*   **Base de Datos**: MySQL/MariaDB. Un motor de base de datos relacional robusto para almacenar toda la información crítica.
*   **Frontend**: Blade (Motor de plantillas de Laravel) con integración de AdminLTE para un panel administrativo responsivo y profesional.
*   **Seguridad**: Implementación de roles y permisos (Spatie) para diferenciar entre administradores y personal.

## 3. Estructura de Datos (Base de Datos)
El corazón del sistema es su base de datos, diseñada para mantener la consistencia de los datos. Las principales entidades son:

*   **Usuarios (`users`)**: Quienes acceden al sistema. Tienen roles asignados (Administrador, Personal).
*   **Personas (`people`)**: Información personal (DNI, Nombre, Apellido) separada de la cuenta de usuario, permitiendo que una persona pueda tener o no un usuario de sistema, o ser simplemente alguien que solicita un recurso.
*   **Recursos (`resources`)**: Los bienes prestables (Proyectores, Notebooks, Aulas). Están categorizados y tienen estados (Disponible, En reparación, etc.).
*   **Reservas (`reservations`)**: El registro central que vincula a una persona (a través de un perfil), uno o más recursos y un periodo de tiempo.
*   **Tablas Pivot**: `reservation_resources` permite que una sola reserva incluya múltiples recursos.

## 4. Validaciones y Reglas de Negocio
Para garantizar que el sistema funcione correctamente y evitar errores humanos o datos inconsistentes, se han implementado múltiples capas de validación. A continuación, se detallan de manera amigable:

### 4.1. Gestión de Usuarios
Al crear o editar usuarios, el sistema verifica:
*   **Datos Obligatorios**: Nombre, Apellido, Email, DNI y Rol son indispensables.
*   **Unicidad**:
    *   El **Email** no puede repetirse en el sistema.
    *   El **DNI** es único; el sistema verifica si la persona ya existe. Si existe y ya tiene usuario, impide crear uno nuevo para evitar duplicados.
*   **Contraseñas**: Deben tener al menos 8 caracteres y confirmarse para evitar errores de tipeo.
*   **Integridad al Eliminar**: No se permite eliminar un usuario si este tiene reservas asociadas. En su lugar, se sugiere desactivarlo, preservando el historial de operaciones.

### 4.2. Gestión de Recursos
Para mantener el inventario ordenado:
*   **Campos Requeridos**: Todo recurso debe tener un nombre, una categoría y un estado inicial.
*   **Consistencia**: El estado y la categoría deben existir en la base de datos (no se pueden inventar categorías al vuelo sin crearlas antes).
*   **Protección al Borrar**: El sistema es inteligente; no permite eliminar un recurso si este forma parte de una reserva activa o pendiente de devolución. Esto evita que desaparezcan recursos que "deberían" estar en uso.

### 4.3. Gestión de Reservas (El Núcleo del Sistema)
Aquí es donde el sistema aplica la mayor cantidad de controles para evitar conflictos:

*   **Coherencia Temporal**:
    *   La **Fecha de Inicio** no puede ser en el pasado (no se puede reservar para ayer).
    *   La **Fecha de Fin** debe ser obligatoriamente posterior a la de inicio.
*   **Selección de Recursos**: Es obligatorio elegir al menos un recurso para crear una reserva.
*   **Control de Disponibilidad (Anti-Solapamiento)**:
    *   Antes de confirmar una reserva, el sistema "mira" la agenda de cada recurso seleccionado.
    *   Si alguno de los recursos ya está ocupado en el rango de horario elegido (aunque sea por un minuto), el sistema **bloquea la operación** y avisa qué recurso está en conflicto. Esto es vital para evitar la doble asignación.
*   **Validación de Identidad (Para Administradores)**:
    *   Cuando un administrador crea una reserva para un tercero, el sistema exige DNI, Nombre y Apellido.
    *   Si la persona no existe, el sistema la crea automáticamente (transparencia para el usuario).
    *   Si ya existe, reutiliza sus datos, evitando duplicar personas en la base de datos.

## 5. Conclusión
El sistema no es solo un registro de datos; es un asistente activo que vigila la integridad de la información. Las validaciones implementadas actúan como "semáforos" que guían al usuario, previenen errores comunes (como solapar reservas o duplicar personas) y aseguran que la administración de los recursos sea fluida y confiable.
