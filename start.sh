#!/bin/bash

# Asegurarse de que el storage tenga los permisos correctos
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Limpiar caché de Laravel
php artisan config:clear
php artisan cache:clear

# Ejecutar migraciones automáticamente
echo "Ejecutando migraciones..."
php artisan migrate --force

# Crear enlace simbólico de storage (si no existe)
php artisan storage:link

# Iniciar Apache en el foreground
echo "Iniciando Apache..."
apache2-foreground
