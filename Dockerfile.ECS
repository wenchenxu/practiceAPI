# Lean single-container image: PHP-FPM + Nginx + Supervisor
FROM php:8.4-fpm-alpine

# System deps (keep it tight)
# RUN apk add --no-cache nginx supervisor libpq postgresql-dev libzip-dev \
#   && docker-php-ext-install pdo pdo_pgsql zip opcache mbstring

RUN set -eux; \
      ver="$(cut -d. -f1,2 /etc/alpine-release)"; \
    { \
      echo "https://mirrors.aliyun.com/alpine/v${ver}/main"; \
      echo "https://mirrors.aliyun.com/alpine/v${ver}/community"; \
      echo "https://mirror.tuna.tsinghua.edu.cn/alpine/v${ver}/main"; \
      echo "https://mirror.tuna.tsinghua.edu.cn/alpine/v${ver}/community"; \
    } > /etc/apk/repositories; \
    apk update

RUN --mount=type=cache,target=/var/cache/apk \
    set -eux; \
    apk add --update-cache --no-progress \
        nginx supervisor libpq libzip oniguruma \
    && apk add --no-cache --virtual .build-deps\
        $PHPIZE_DEPS postgresql-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_pgsql zip opcache mbstring \
    && apk del .build-deps

# PHP-FPM pool: log to stderr and listen on TCP 127.0.0.1:9000
RUN sed -i -e 's/;catch_workers_output\s*=.*/catch_workers_output = yes/' /usr/local/etc/php-fpm.d/www.conf \
  && sed -i -e 's|^listen\s*=.*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

# Composer (use official binary)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps with caching: copy composer files first
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader || true

# Now copy the rest of the app
COPY . .

# Permissions for Laravel
RUN mkdir -p storage/framework/{cache/data,sessions,views} bootstrap/cache \
  && chown -R www-data:www-data storage bootstrap/cache \
  && find storage bootstrap/cache -type d -exec chmod 775 {} \; \
  && find storage bootstrap/cache -type f -exec chmod 664 {} \;

# Nginx + Supervisor configs
COPY docker-config/nginx.conf       /etc/nginx/http.d/default.conf
COPY docker-config/supervisord.conf /etc/supervisor/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord","-c","/etc/supervisor/supervisord.conf"]
