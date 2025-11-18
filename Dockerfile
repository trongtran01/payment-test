FROM php:8.2-apache

# Cài package cần thiết
RUN apt-get update && apt-get install -y \
    unzip git libzip-dev \
    && docker-php-ext-install zip

RUN a2enmod rewrite

# Copy toàn bộ source
COPY . /var/www/html
WORKDIR /var/www/html

# Cài composer
COPY src/ /var/www/html
WORKDIR /var/www/html
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install
