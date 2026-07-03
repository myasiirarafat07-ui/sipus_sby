#!/bin/sh
set -e

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Seed database only if it hasn't been seeded yet
echo "Checking if database needs seeding..."
php artisan tinker --execute="if (\App\Models\User::count() === 0) { echo 'Seeding database...'; \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]); }"

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
echo "Starting nginx..."
exec nginx -g 'daemon off;'
