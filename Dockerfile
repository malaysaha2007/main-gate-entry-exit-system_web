FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libssl-dev \
    pkg-config \
    git \
    unzip

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install mysqli

COPY . /var/www/html/

RUN a2enmod rewrite