FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
        curl unzip git libonig-dev libzip-dev libxml2-dev sqlite3 \
        libpng-dev libjpeg62-turbo-dev libwebp-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install pdo_sqlite mbstring zip bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Build-time setup: install production dependencies, initialize and seed public-content SQLite database
RUN composer install --no-dev --optimize-autoloader \
    && cp .env.example .env \
    && touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --class=Database\\Seeders\\PublicPreviewSeeder --force \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs public/images/optimized \
    && chmod +x docker-entrypoint.sh \
    && chown -R www-data:www-data storage bootstrap/cache database public/images/optimized \
    && chmod -R u+rwX,g+rwX,o=rX storage bootstrap/cache database public/images/optimized

USER www-data

ENV PORT=8080
EXPOSE 8080

# Runtime: require stable APP_KEY, fail fast if missing, execute single-command HTTP server
CMD ["./docker-entrypoint.sh"]
