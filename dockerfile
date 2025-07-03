FROM php:8.2-apache

# Install system dependencies and PHP extensions including PostgreSQL support
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    curl \
  && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    zip \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy Composer and install PHP dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Configure Apache virtual host
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Set proper permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Run database migrations and start Apache in foreground
CMD ["bash", "-lc", "php artisan migrate --force && apache2-foreground"]
