FROM php:8.4.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libxml2-dev \
    curl \
    zip \
    && docker-php-ext-install pdo_mysql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/backend

# # Copy Composer dependency files
# COPY composer.json composer.lock ./

# Copy Laravel application code
COPY . .

# Install PHP dependencies via Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts -vvv

# Create storage directories and set permissions for laravel
RUN mkdir -p /var/www/backend/bootstrap/cache \
            /var/www/backend/storage/framework/cache \
            /var/www/backend/storage/framework/sessions \
            /var/www/backend/storage/framework/views \
            /var/www/backend/storage/logs \
    && chown -R www-data:www-data /var/www/backend/storage \
    && chown -R www-data:www-data /var/www/backend \
    && chmod -R 755 /var/www/backend \
    && chmod -R 775 /var/www/backend/storage \
    && chmod -R 775 /var/www/backend/bootstrap/cache

# Configure PHP-FPM to listen to port load_balancer via port 9000
RUN sed -i 's|listen = .*|listen = 0.0.0.0:9000|' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 9000
CMD ["php-fpm"]

#RUN php artisan package:discover --ansi