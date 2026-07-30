FROM php:8.3-cli

# Install ekstensi PHP yang diperlukan
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy file composer terlebih dahulu (optimasi cache Docker)
COPY composer.json composer.lock ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy semua file project
COPY . .

# Setup environment dan database
RUN cp .env.example .env \
    && php artisan key:generate --force \
    && mkdir -p database \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database \
    && php artisan migrate --force \
    && php artisan optimize:clear

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
