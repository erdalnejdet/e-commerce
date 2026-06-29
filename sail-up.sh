#!/bin/bash
set -e

cd "$(dirname "$0")"

echo "==> Composer bagimliliklari (Sail dahil) kuruluyor..."
if [ -f vendor/bin/sail ]; then
    echo "Sail zaten kurulu."
else
    docker run --rm -v "$(pwd)":/app -w /app composer:2 composer install --ignore-platform-reqs
fi

if [ ! -d vendor/laravel/sail/runtimes/8.3 ]; then
    echo "HATA: vendor/laravel/sail/runtimes/8.3 bulunamadi."
    exit 1
fi

echo "==> Docker konteynerleri baslatiliyor..."
docker compose up -d --build

echo "==> Hazir! http://localhost"
