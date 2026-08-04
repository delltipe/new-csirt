FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
        curl unzip git libonig-dev libzip-dev libxml2-dev sqlite3 \
    && docker-php-ext-install pdo_sqlite mbstring zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader \
    && cp .env.example .env \
    && php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');" \
    && chmod -R 777 storage bootstrap/cache

ENV PORT=8000
EXPOSE 8000

CMD ["sh", "-c", "php artisan key:generate --force --quiet && php artisan migrate --force --seed && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT"]
