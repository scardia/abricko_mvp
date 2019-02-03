FROM php:5.6-apache

RUN docker-php-ext-install pdo pdo_mysql mysql mysqli

COPY production/ /var/www/html

