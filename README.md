# ProductosPro

Aplicación web de inventario y tienda construida con Laravel 10. Incluye catálogo de productos, autenticación, carrito, pedidos, perfiles y administración.

## Funcionalidades

- Catálogo con búsqueda, carrusel de productos e imágenes.
- Registro e inicio de sesión con nombre de usuario y contraseña; Google/Firebase es opcional.
- Protección reCAPTCHA obligatoria para el inicio de sesión local.
- Roles para usuarios y administradores.
- Carrito de compras y administración de productos e inventario.
- Checkout que registra pedidos y descuenta existencias.
- Opciones visuales de pago (Bancolombia, Mastercard y Davivienda/Daviplata) que **no procesan cobros**. Los pedidos quedan pendientes de pago y un administrador confirma manualmente el pago después de coordinarlo por separado.

## Requisitos

- PHP 8.2 o superior y extensiones requeridas por Laravel (incluidas `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd` y `zip`).
- Composer 2.
- MySQL 8.
- Node.js 22 LTS y npm.

## Instalación local

1. Clona el proyecto e instala sus dependencias:

   ```bash
   git clone <URL_DEL_REPOSITORIO>
   cd ProyectoIntegrador-main
   composer install
   npm ci
   ```

2. Crea la configuración local y genera una clave:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   En Windows PowerShell, usa `Copy-Item .env.example .env` en lugar de `cp`.

3. Configura en `.env` la conexión a una base de datos MySQL vacía:

   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=productospro
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contrasena
   ```

   Configura las claves reales `RECAPTCHA_SITE_KEY` y `RECAPTCHA_SECRET_KEY` desde la consola de reCAPTCHA y autoriza el dominio local. Sin estas claves, el inicio de sesión local falla de forma segura.

4. Crea el esquema, prepara el almacenamiento y, si quieres, los datos de ejemplo:

   ```bash
   php artisan migrate
   php artisan storage:link
   php artisan db:seed
   ```

   El seeder no crea una cuenta administrativa predeterminada. Para crear un administrador inicial, configura `BOOTSTRAP_ADMIN_USERNAME`, `BOOTSTRAP_ADMIN_PASSWORD` (mínimo 12 caracteres), `BOOTSTRAP_ADMIN_EMAIL` y `BOOTSTRAP_ADMIN_NAME` en `.env` antes de ejecutar `php artisan db:seed`. El seeder no cambia la contraseña de un administrador existente.

   **Base de datos existente:** no importes `database/ProductosPro.sql` ni ejecutes `migrate:fresh`. Antes de migrar una base heredada, haz una copia de seguridad y reconcilia el historial de migraciones con el esquema real.

5. Inicia el servidor y compila los recursos:

   ```bash
   npm run build
   php artisan serve
   ```

   En desarrollo, puedes usar `npm run dev` en otra terminal.

## Configuración opcional de Google

El inicio de sesión de Google usa Firebase Authentication. Configura las variables `FIREBASE_*` del `.env`, habilita Google en Firebase Console y autoriza el dominio. El correo de Firebase debe estar verificado antes de iniciar sesión. No es necesario configurar Firebase para utilizar el acceso local.

## Pruebas y compilación

Las pruebas usan SQLite en memoria:

```bash
php artisan test
npm run build
```

GitHub Actions ejecuta estas pruebas y comprueba la compilación de Vite en cada `push` y pull request.

## Docker Compose (opcional)

Crea el archivo `.env` local y define contraseñas distintas para la aplicación y para la cuenta root de MySQL:

```dotenv
DB_PASSWORD=contrasena_unica_para_la_aplicacion
DB_ROOT_PASSWORD=otra_contrasena_unica_para_root
```

Luego ejecuta `docker compose up --build`. Los puertos de MySQL y Mailpit se publican solo en la interfaz local.

## Documentación

- [Arquitectura](./arquitectura.md)
