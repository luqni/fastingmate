# Build Frontend Assets
FROM node:20-alpine as build

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# Build PHP App
FROM php:8.2-fpm

# Install dependencies (GD, zip, zlib, PostgreSQL)
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip zlib1g-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_pgsql gd pcntl bcmath

RUN pecl install redis \
    && docker-php-ext-enable redis

# Copy Composer from the composer image
COPY --from=composer /usr/bin/composer /usr/local/bin/composer


# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Pastikan folder penting ada
RUN mkdir -p storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Copy frontend assets
COPY --from=build /app/public/build /var/www/public/build

# Laravel permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www/storage

# Supervisor config
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/laravel.conf /etc/supervisor/conf.d/laravel.conf

# Entrypoint
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]