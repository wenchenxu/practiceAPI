# Base image: Ubuntu 24.04 (Noble) based PHP image
FROM php:8.4-fpm AS base

# ---- neutralize host proxy leaks during build ----
# Clear common proxy environment variables so apt/curl don't try localhost:7890
ARG http_proxy=
ARG https_proxy=
ARG HTTP_PROXY=
ARG HTTPS_PROXY=
ARG all_proxy=
ARG ALL_PROXY=
ARG no_proxy=
ARG NO_PROXY=
ENV http_proxy= \
    https_proxy= \
    HTTP_PROXY= \
    HTTPS_PROXY= \
    all_proxy= \
    ALL_PROXY= \
    no_proxy= \
    NO_PROXY=

# Tell apt to never use a proxy (belt & suspenders)
RUN printf 'Acquire::http::Proxy "false";\nAcquire::https::Proxy "false";\n' \
    > /etc/apt/apt.conf.d/99noproxy

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# System packages & PHP extensions
RUN apt-get update \
 && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
      nginx supervisor curl git unzip bash \
      libpq-dev libzip-dev libonig-dev \
 && docker-php-ext-configure opcache --enable-opcache \
 && docker-php-ext-install -j"$(nproc)" mbstring pdo_pgsql zip opcache \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Create runtime dirs
RUN mkdir -p /var/log/supervisor /var/run/php /var/run/nginx

# Copy configs (adjust paths if yours are different)
COPY docker-config/nginx.conf /etc/nginx/nginx.conf
COPY docker-config/conf.d/*.conf /etc/nginx/conf.d/
COPY docker-config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# App code
WORKDIR /var/www/html
COPY . .

# Expose php-fpm port (for supervisor/nginx inside container)
EXPOSE 9000

# Entrypoint: run both php-fpm and nginx under Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
