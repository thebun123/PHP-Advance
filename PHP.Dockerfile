FROM php:8.1-fpm

RUN docker-php-ext-install mysqli pdo_mysql

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d
