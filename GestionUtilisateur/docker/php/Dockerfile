FROM php:8.1-fpm

# Installer les extensions nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-install intl zip pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www/html
