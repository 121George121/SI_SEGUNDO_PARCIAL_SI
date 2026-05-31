# =============================================================================
# Etapa 1: Compilar assets frontend (Vite + Tailwind)
# =============================================================================
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json .npmrc ./
RUN npm ci

COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

RUN npm run build

# =============================================================================
# Etapa 2: Instalar dependencias PHP (Composer)
# =============================================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

# --no-autoloader: evita error porque composer.json referencia app/Models
# antes de copiar el código fuente
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --no-scripts \
    --no-autoloader

COPY . .

RUN composer dump-autoload --optimize --no-scripts

# =============================================================================
# Etapa 3: Imagen final de producción
# =============================================================================
FROM php:8.3-cli-alpine

WORKDIR /var/www/html

# Extensiones PHP requeridas por Laravel y MySQL (Railway)
RUN apk add --no-cache \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
        pcntl \
        intl \
    && rm -rf /var/cache/apk/*

# Copiar aplicación y dependencias
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

# Permisos para storage y cache de Laravel
RUN mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Railway inyecta la variable PORT automáticamente
EXPOSE 8080

ENTRYPOINT ["docker-entrypoint.sh"]
