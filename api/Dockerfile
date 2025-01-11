FROM php:8.2-fpm

# Installer les dépendances et extensions nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libpq-dev \
    git \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code source de l'application
COPY . .

# Installer les dépendances Symfony
RUN composer install --no-interaction --optimize-autoloader

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
