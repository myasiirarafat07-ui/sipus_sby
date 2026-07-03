#!/bin/sh
set -e

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
echo "Starting nginx..."
exec nginx -g 'daemon off;'
