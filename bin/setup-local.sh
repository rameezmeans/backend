#!/usr/bin/env bash
set -eu
if (set -o 2>/dev/null | grep -q pipefail); then
  set -o pipefail
fi

# Run from repo root or anywhere:
cd "$(dirname "$0")/.."

echo "→ composer install"
composer install --no-dev -o

echo "→ Filament v2→v4 adapter shims"
FORMS_DIR="vendor/filament/forms/dist"
NOTIF_DIR="vendor/filament/notifications/dist"

if [ -d "$FORMS_DIR" ]; then
  cat > "$FORMS_DIR/module.esm" <<'EOF'
import './index.js';
export default function () {}
EOF
  ln -sf index.css "$FORMS_DIR/module.esm.css"
fi

if [ -d "$NOTIF_DIR" ]; then
  cat > "$NOTIF_DIR/module.esm" <<'EOF'
import './index.js';
export default function () {}
EOF
  ln -sf index.css "$NOTIF_DIR/module.esm.css"
fi

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