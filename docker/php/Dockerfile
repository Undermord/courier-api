FROM php:7.4-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libicu-dev

# Устанавливаем расширения PHP
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    zip \
    intl

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Устанавливаем рабочую директорию
WORKDIR /app 