#!/bin/bash
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "APP_KEY not set — generating..."
  php artisan key:generate --force
fi

echo "Waiting for database..."
until php artisan db:monitor --databases=mysql 2>/dev/null; do
  sleep 2
done

echo "Running migrations..."
php artisan migrate --force

echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Starting services..."
mkdir -p /var/log/supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
