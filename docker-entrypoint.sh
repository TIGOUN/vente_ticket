#!/usr/bin/env bash
set -euo pipefail

cd /var/www/html

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan storage:link || true

php artisan migrate --force

exec "$@"
