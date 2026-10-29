#!/bin/bash
set -e

# Asegurarse de que el storage tenga los permisos correctos
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Limpiar caché de Laravel
php artisan config:clear
php artisan cache:clear

# Ejecutar migraciones automáticamente
echo "Ejecutando migraciones..."
php artisan migrate --force

# Cargar de forma segura los roles y productos de ejemplo que falten
echo "Cargando datos iniciales..."
php artisan db:seed --force

# Crear enlace simbólico de storage (si no existe)
php artisan storage:link

# Iniciar Apache en el foreground
echo "Iniciando Apache..."
apache2-foreground
