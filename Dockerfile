# 1. Build Frontend Assets
FROM node:20-alpine AS build

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .

# Tambahkan build argument untuk VAPID key
ARG VITE_VAPID_PUBLIC_KEY
ENV VITE_VAPID_PUBLIC_KEY=$VITE_VAPID_PUBLIC_KEY

# Jalankan build frontend
RUN npm run build

# 2. Build PHP App
FROM php:8.2-fpm

# Install dependencies (GD, zip, zlib, PostgreSQL, cron)
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip zlib1g-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev supervisor cron libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_pgsql gd pcntl bcmath intl

RUN pecl install redis \
    && docker-php-ext-enable redis

# Copy Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Pastikan folder penting ada sebelum install composer
RUN mkdir -p storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Copy frontend assets dari stage 'build'
# Pastikan path /app/public/build benar sesuai output Vite Anda
COPY --from=build /app/public/build /var/www/public/build
COPY --from=build /app/public/sw.js /var/www/public/
# Menggunakan * untuk menangkap semua file workbox
COPY --from=build /app/public/workbox-*.js /var/www/public/

# Set permissions (Sangat penting untuk Laravel)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Supervisor & Entrypoint
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/laravel.conf /etc/supervisor/conf.d/laravel.conf

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
