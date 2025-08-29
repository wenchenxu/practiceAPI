# Example base — adjust if you’re already using a specific image
FROM php:8.4-fpm-alpine

# System deps + PHP extensions
RUN apk add --no-cache \
      nginx supervisor \
      libpq postgresql-dev \
      libzip-dev oniguruma-dev \
  && docker-php-ext-configure opcache --enable-opcache \
  && docker-php-ext-install \
      mbstring \
      pdo_pgsql \
      zip \
      opcache

# ✅ Add composer (copy the binary from the official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Workdir
WORKDIR /var/www/html

# Copy app
COPY . .

# PHP-FPM config (optional, if you have custom pool files)
# COPY docker-config/php-fpm.conf /usr/local/etc/php-fpm.conf
# COPY docker-config/php-fpm-pool.conf /usr/local/etc/php-fpm.d/www.conf

# Nginx and Supervisor configs
COPY docker-config/nginx.conf       /etc/nginx/http.d/default.conf
COPY docker-config/supervisord.conf /etc/supervisor/supervisord.conf

# Permissions
RUN mkdir -p storage/logs storage/framework/views \
 && chown -R www-data:www-data storage bootstrap/cache

# Expose Nginx port
EXPOSE 80

# Entrypoint via Supervisor (runs php-fpm + nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]