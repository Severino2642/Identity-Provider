# Utiliser l'image PHP 8.2 avec FPM
FROM php:8.2-fpm

# Installer les extensions nécessaires pour Symfony et PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code de l'application dans le conteneur
COPY . /var/www/html

# Donner les permissions nécessaires pour Symfony
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/var

# Exposer le port 9000 (utilisé par PHP-FPM)
EXPOSE 9000

