#!/bin/bash
set -e

echo "Starting Laravel container setup..."
echo "Setting Laravel permissions..."

# Ensure directories exist
mkdir -p /var/www/backend/storage/logs \
         /var/www/backend/storage/framework/cache \
         /var/www/backend/storage/framework/sessions \
         /var/www/backend/storage/framework/views \
         /var/www/backend/storage/app \
         /var/www/backend/bootstrap/cache

# Set ownership and permissions for mounted volumes
chown -R www-data:www-data /var/www/backend/storage
chown -R www-data:www-data /var/www/backend/bootstrap/cache
chmod -R 775 /var/www/backend/storage
chmod -R 775 /var/www/backend/bootstrap/cache

echo "Permissions set successfully!"

# Install/update composer dependencies if needed
echo "Checking Composer dependencies..."
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader --no-scripts
else
    echo "Composer dependencies already installed."
fi

echo "Starting php-fpm..."
exec "$@"