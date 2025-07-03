FROM php:8.2-apache

# Install dependencies termasuk libpq-dev untuk PostgreSQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \               # <- library development PostgreSQL
    zip \
    unzip \
    git \
    curl \
  && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \              # <- aktifkan driver pgsql
    zip

# Aktifkan mod_rewrite agar Laravel bisa menggunakan pretty URLs
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy composer binary dan install Laravel dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --optimize-autoloader --no-dev

# Gunakan konfigurasi Apache dari folder docker/
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Set permission folder storage dan bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Saat container start, jalankan migrasi lalu start Apache
CMD php artisan migrate --force && apache2-foreground
