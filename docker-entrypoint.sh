#!/bin/sh
set -e

cd /var/www/html

# Enlace simbólico para archivos públicos
php artisan storage:link --force 2>/dev/null || true

# Optimizar Laravel para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones al iniciar el contenedor
php artisan migrate --force

# Railway asigna el puerto dinámicamente via $PORT
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
