FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip

RUN docker-php-ext-install zip

COPY . /var/www/html/

RUN a2enmod rewrite

EXPOSE 80