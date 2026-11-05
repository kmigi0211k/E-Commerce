# ProductosPro

<div align="center">

### Una tienda en línea con inventario, pedidos y compromiso comunitario

Explora productos, gestiona compras y participa en iniciativas de voluntariado desde una sola plataforma.

[![Laravel](https://img.shields.io/badge/Laravel-10-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Deploy en Render](https://img.shields.io/badge/Deploy-Render-46E3B7?logo=render&logoColor=111)](https://proyectointegrador-m084.onrender.com)

**[Ver ProductosPro en vivo](https://proyectointegrador-m084.onrender.com)**

</div>

---

## Sobre el proyecto

ProductosPro es una aplicación web desarrollada con Laravel para administrar un catálogo de productos y ofrecer una experiencia de compra con carrito, pedidos y seguimiento. También incluye un espacio de voluntariado comunitario y herramientas de administración.

## Lo que puedes hacer

| Área | Funcionalidades |
| --- | --- |
| **Tienda** | Explorar y buscar productos, filtrar por precio, ordenar resultados y consultar detalles. |
| **Cuenta** | Registrarse con verificación por correo, iniciar sesión y gestionar el perfil. Google mediante Firebase está disponible como opción configurable. |
| **Compras** | Añadir productos al carrito, finalizar un pedido, consultar el historial y revisar el estado de la compra. |
| **Comunidad** | Consultar productos solidarios, enviar postulaciones de voluntariado y revisar su estado. |
| **Administración** | Gestionar productos e inventario, revisar pedidos, administrar usuarios y responder a postulaciones. |

> **Importante:** los métodos de pago que muestra el checkout son informativos. La aplicación no procesa cobros en línea; los pedidos quedan pendientes hasta que el administrador confirme el pago por separado.

## Tecnologías

- **Backend:** PHP 8.2+, Laravel 10 y Eloquent ORM.
- **Interfaz:** Blade, Bootstrap 5, Bootstrap Icons, Alpine.js y Vite.
- **Datos:** MySQL en desarrollo local; PostgreSQL en Render.
- **Autenticación:** sesiones Laravel, verificación de correo, reCAPTCHA y Firebase Authentication opcional.
- **Despliegue:** Docker y Render.

## Puesta en marcha local

### Requisitos

- PHP 8.2 o superior con las extensiones requeridas por Laravel, incluido el controlador PDO de MySQL (`pdo_mysql`).
- Composer 2.
- MySQL 8.
- Node.js 22 LTS y npm.

### Instalación en Windows / Laragon

Clona el repositorio e instala las dependencias:

```powershell
git clone https://github.com/kmigi0211k/E-Commerce.git
Set-Location E-Commerce
composer install
npm ci
```

Prepara la configuración local:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Crea una base de datos vacía en MySQL y configura estos valores en `.env`:

```dotenv
APP_NAME=ProductosPro
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=productospro
DB_USERNAME=root
DB_PASSWORD=
```

Configura `RECAPTCHA_SITE_KEY` y `RECAPTCHA_SECRET_KEY` con claves válidas para `localhost`; son necesarias para iniciar sesión con usuario y contraseña. El registro también requiere un servicio de correo configurado para enviar el código de verificación.

Crea el esquema, prepara el almacenamiento y carga los productos de ejemplo:

```powershell
php artisan migrate
php artisan storage:link
php artisan db:seed
npm run build
php artisan serve
```

Abre <http://127.0.0.1:8000>. Para desarrollo de interfaz, ejecuta `npm run dev` en otra terminal.

### Cuenta de administración

El proyecto **no incluye credenciales de administrador predeterminadas**. Para crear el primer administrador, configura las siguientes variables antes de ejecutar el seeder:

```dotenv
BOOTSTRAP_ADMIN_USERNAME=tu_usuario
BOOTSTRAP_ADMIN_PASSWORD=una_clave_unica_de_al_menos_12_caracteres
BOOTSTRAP_ADMIN_EMAIL=tu_correo
BOOTSTRAP_ADMIN_NAME=Nombre del administrador
```

No publiques ni compartas claves reales. El seeder no reemplaza la contraseña de un administrador existente.

### Integraciones opcionales

- **Google:** configura las variables `FIREBASE_*`, habilita Google en Firebase Authentication y autoriza el dominio de la aplicación.
- **reCAPTCHA:** registra los dominios donde correrá el sitio y añade las claves correspondientes al entorno. En producción, Render debe tener configuradas las variables secretas.

## Pruebas y compilación

Las pruebas automatizadas usan SQLite en memoria:

```powershell
php artisan test
npm run build
```

GitHub Actions ejecuta las pruebas y verifica la compilación de recursos en cada push y pull request.

## Despliegue

La aplicación de producción está publicada en **[Render](https://proyectointegrador-m084.onrender.com)**. La configuración de servicio y base de datos está en [`render.yaml`](./render.yaml); el contenedor se define en [`Dockerfile`](./Dockerfile).

La configuración del despliegue requiere secretos y variables de entorno en Render. No agregues claves de aplicación, contraseñas, credenciales de correo, Firebase o reCAPTCHA al repositorio.

## Estructura del proyecto

```text
app/                 Controladores, modelos, validación y servicios
database/            Migraciones, fábricas, seeders y documentación de datos
resources/views/     Páginas y componentes Blade
resources/js/        Punto de entrada JavaScript y Bootstrap
resources/css/       Estilos de Tailwind
routes/              Rutas web y API
tests/               Pruebas automatizadas
```

## Documentación

- [Arquitectura de la aplicación](./arquitectura.md)
- [Historias de usuario](./user_stories.md)
- [Documentación de la base de datos](./database/DOCUMENTACION_BD_RELACIONAL.md)

> Si trabajas con una base de datos que ya contiene información, realiza una copia de seguridad antes de migrar. No ejecutes `migrate:fresh` en una base que quieras conservar.
