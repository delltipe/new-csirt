#!/bin/sh
set -e

if [ -z "$APP_KEY" ]; then
    echo "[FATAL] APP_KEY environment variable is missing. Set APP_KEY in blitz.cloud environment settings." >&2
    exit 1
fi

PORT="${PORT:-8080}"

echo "Starting Jakarta CSIRT Portal (blitz.cloud Read-Only Public Showcase) on port ${PORT}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT}" --no-reload
