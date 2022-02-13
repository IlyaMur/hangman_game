FROM php:8.1.2

RUN docker-php-source extract && docker-php-ext-install pdo_mysql mysqli && docker-php-source delete

COPY . /app
