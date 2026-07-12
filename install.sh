#!/usr/bin/env sh
set -eu

if ! command -v docker >/dev/null 2>&1; then
    echo "Docker is required. Please install/start Docker, then run this script again."
    exit 1
fi

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Created .env from .env.example"
fi

if [ ! -f vendor/bin/sail ]; then
    echo "Installing Composer dependencies with Docker so Sail becomes available..."

    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php84-composer:latest \
        composer install --ignore-platform-req=ext-intl --ignore-platform-req=ext-exif
fi

echo "Starting Laravel Sail..."
./vendor/bin/sail up -d

echo "Waiting for MySQL to be reachable from the Laravel container..."
attempt=1
until ./vendor/bin/sail php -r '$env = parse_ini_file(".env") ?: []; $host = $env["DB_HOST"] ?? "mysql"; $port = $env["DB_PORT"] ?? "3306"; $user = $env["DB_USERNAME"] ?? "sail"; $pass = $env["DB_PASSWORD"] ?? "password"; try { new PDO("mysql:host={$host};port={$port}", $user, $pass); exit(0); } catch (Throwable $e) { fwrite(STDERR, $e->getMessage() . PHP_EOL); exit(1); }' >/dev/null 2>&1; do
    if [ "$attempt" -ge 60 ]; then
        echo "MySQL was not reachable after 60 seconds."
        echo "Run './vendor/bin/sail ps' and confirm both 'laravel.test' and 'mysql' are running."
        exit 1
    fi

    attempt=$((attempt + 1))
    sleep 1
done

echo "Installing frontend dependencies..."
./vendor/bin/sail npm install

echo "Preparing Laravel..."
./vendor/bin/sail artisan key:generate --force
./vendor/bin/sail artisan storage:link
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan optimize:clear

echo "Building frontend assets..."
./vendor/bin/sail npm run build

echo ""
echo "Installation complete."
echo "Website:     http://localhost:8081"
echo "Admin panel: http://localhost:8081/admin"
