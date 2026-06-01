#!/usr/bin/env bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/ris_v1}"
REPO_URL="${REPO_URL:-https://github.com/hunter1431/ris_v1.git}"
BRANCH="${BRANCH:-main}"

sudo mkdir -p "$APP_DIR"
sudo chown -R "$USER":"$USER" "$APP_DIR"

if [ ! -d "$APP_DIR/.git" ]; then
  git clone --branch "$BRANCH" "$REPO_URL" "$APP_DIR"
fi

cd "$APP_DIR"
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci
npm run build

if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate --force
fi

php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart || true

sudo chown -R www-data:www-data storage bootstrap/cache
sudo systemctl reload php8.2-fpm || true
sudo systemctl reload nginx || true
