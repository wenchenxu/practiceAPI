# Dockerfile

# Use a specific PHP version with FPM (FastCGI Process Manager) on a lightweight Alpine Linux base
FROM php:8.4-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/html

# Install system dependencies needed for Laravel
# We add "apk update" first to get the latest package list.
# Install system dependencies and temporary build tools in one go

RUN apk update && apk add --no-cache \
    # System dependencies needed by Laravel & PHP extensions
    nginx \
    supervisor \
    libzip-dev \
    zip \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    nodejs \
    npm \
    # --virtual .build-deps installs these packages as a temporary group
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    # Now, configure, install, and enable PHP extensions
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    zip \
    pdo \
    pdo_pgsql \
    # Finally, clean up the temporary build tools
    && apk del .build-deps

# Install Composer (PHP package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application code to the container
COPY . .

# Install Composer dependencies
RUN composer install --no-interaction --no-plugins --no-scripts --optimize-autoloader

# Install NPM dependencies and build front-end assets
RUN npm install
RUN npm run build

# Set correct file permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 9000 to listen for PHP-FPM requests
EXPOSE 9000

# The command to run when the container starts
CMD ["php-fpm"]