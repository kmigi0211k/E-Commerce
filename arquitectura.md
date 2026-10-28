# Arquitectura de ProductosPro

ProductosPro es una aplicación Laravel 10 basada en el patrón MVC. Las vistas Blade y los recursos de Vite proporcionan la interfaz; los controladores gestionan los flujos de autenticación, catálogo, carrito y pedidos; Eloquent accede a MySQL.

## Componentes principales

- `app/Models`: modelos `User`, `Person`, `Product`, `Order` y sus relaciones.
- `app/Http/Controllers`: endpoints de autenticación, perfil, productos, carrito, pedidos y administración.
- `app/Http/Requests`: validación de solicitudes de autenticación y perfil.
- `database/migrations`: esquema versionado para instalaciones nuevas.
- `resources/views`: páginas Blade para tienda, cuenta, pedidos y administración.
- `public/js` y `resources`: interactividad de la interfaz y recursos compilados con Vite.

## Identidad y autenticación

Las cuentas relacionan `users` con `people`; el nombre de usuario identifica el acceso local. El flujo local valida la contraseña, verifica reCAPTCHA y rechaza cuentas inactivas. Los correos de perfil no se pueden cambiar desde el formulario de datos personales, ya que el correo verificado también se utiliza para vincular la identidad de Firebase.

Google/Firebase es opcional. Laravel valida el ID token en el servidor y exige un correo verificado antes de iniciar la sesión. Las cuentas creadas mediante Firebase permanecen pendientes hasta la verificación del correo.

## Carrito y pedidos

El carrito de sesión almacena las selecciones antes del checkout. Al crear un pedido, el sistema guarda sus artículos y descuenta las existencias. No existe una pasarela ni se realiza un cobro en línea: los métodos que aparecen en la interfaz son opciones informativas. El pedido comienza en estado `pending_payment` y el administrador puede marcarlo como completado después de confirmar el pago por separado.

## Base de datos y despliegue

Las migraciones describen instalaciones nuevas. Las bases heredadas o importadas requieren comparar cuidadosamente su esquema con el historial de migraciones antes de ejecutar migraciones pendientes. No se debe ejecutar `migrate:fresh` en una base con datos que se quieran conservar.

Las credenciales, claves de aplicación, Firebase y reCAPTCHA se configuran mediante variables de entorno; los secretos locales no forman parte del repositorio. La documentación de instalación está en el [README](./README.md).
