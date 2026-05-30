FROM php:8.2-fpm

# Install sistem dependensi dasar
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git mariadb-client \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Set owner folder untuk keamanan Nginx/PHP-FPM
RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]