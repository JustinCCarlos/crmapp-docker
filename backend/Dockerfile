FROM php:8.4.1-fpm

# install deps
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libonig-dev libxml2-dev curl zip \
    && docker-php-ext-install pdo_mysql

# install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend

# Copy only composer files first (to leverage Docker cache)
COPY composer.json composer.lock ./

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts -vvv

COPY . .

# Laravel permissions
RUN chown -R www-data:www-data /var/www/backend \
    && chmod -R 775 /var/www/backend/storage /var/www/backend/bootstrap/cache


RUN sed -i 's|listen = .*|listen = 0.0.0.0:9000|' /usr/local/etc/php-fpm.d/www.conf


RUN php artisan package:discover --ansi