# docker/php/Dockerfile
# PHP 8.3 image for Laravel 11

FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    shadow

# PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Fix file permissions
RUN usermod -u 1000 www-data

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
