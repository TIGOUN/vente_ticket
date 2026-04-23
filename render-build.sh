#!/usr/bin/env bash
set -o errexit

echo "--> Installing PHP dependencies"
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "--> Installing Node dependencies"
npm ci

echo "--> Building frontend assets"
npm run build

echo "--> Caching configuration"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--> Creating storage symbolic link"
php artisan storage:link || true

echo "--> Running database migrations"
php artisan migrate --force

echo "--> Build finished"
