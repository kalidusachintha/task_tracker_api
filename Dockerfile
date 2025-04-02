#Trask tracker api backend Dockerfile
FROM php:8.2-fpm

#Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev

#Clear the cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/

#Install PHP extensions
RUN docker-php-ext-install docker-php-ext-configure gd --with-freetype --with-jpeg pdo_mysql mbstring exif pcntl bcmath gd zip

#Install latest composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#Working directory
WORKDIR /var/www/laravel

#Copy existing application directory
COPY ./task_tracker_api /var/www/laravel

#Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

#Set permissions
RUN chown -R www-data:www-data /var/www/laravel \
    && chmod -R 755 /var/www/laravel/storage

#Expose port 9000 for php-fpm
EXPOSE 9000

CMD ["php-fpm"]
