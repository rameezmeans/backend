#!/usr/bin/env bash
set -eu
if (set -o 2>/dev/null | grep -q pipefail); then
  set -o pipefail
fi

# Run from repo root or anywhere:
cd "$(dirname "$0")/.."

#composer require filament/filament:^3 filament/forms:^3 filament/notifications:^3 livewire/livewire:^3

echo "→ composer install"
composer install --no-dev --prefer-dist --no-interaction

echo "→ npm deps & build"
npm ci
npm run build

echo "→ Laravel post-steps"
php artisan storage:link || true

# Generate APP_KEY only if empty/missing
if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=.*[A-Za-z0-9]' .env >/dev/null; then
  php artisan key:generate || true
fi

# Initialize DB if needed
php artisan optimize:clear
php artisan up
php artisan migrate --force


echo "✅ Setup finished"
