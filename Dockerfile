FROM php:8.3-apache

# Install ekstensi PHP yang diperlukan
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy file composer terlebih dahulu (optimasi cache Docker)
COPY composer.json composer.lock ./

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy semua file project
COPY . .

# Buat file SQLite dan set permission
RUN mkdir -p database && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database \
    && chown -R www-data:www-data storage bootstrap/cache database

# Konfigurasi Apache agar point ke /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf \
    && echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Generate app key dan jalankan migrasi saat build
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan migrate --force

EXPOSE 80

CMD ["apache2-foreground"]
