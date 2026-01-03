#!/bin/sh

set -e

echo ">>> Running Composer install..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo ">>> Running Laravel setup..."
php artisan storage:link || true

# Optional: Cache optimizations (bisa diaktifkan untuk production)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan migrasi jika perlu
php artisan migrate --force

echo ">>> Starting Supervisor..."
# -n supaya supervisor jadi PID 1 di container (bukan background)
exec supervisord -n -c /etc/supervisor/supervisord.conf