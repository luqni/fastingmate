# Stage 1: Build Assets (Memastikan CSS/JS tersedia)
FROM node:20 AS build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Run App (Sesuai request user)
FROM php:8.2-fpm

# Install dependencies (tambahkan libpng dan gd)
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip zlib1g-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libpq-dev supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_pgsql gd pcntl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Copy built assets from Stage 1
COPY --from=build /app/public/build public/build

# Pastikan folder penting ada
RUN mkdir -p storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Laravel permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www/storage

# Copy Entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Entrypoint script handles migrations & cache
ENTRYPOINT ["entrypoint.sh"]

# Jalankan Laravel server (passed to ENTRYPOINT)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
