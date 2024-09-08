FROM php:8.3

RUN apt-get update \
    && apt-get install -y sqlite3 libsqlite3-dev libpng-dev libmcrypt-dev libxml2-dev libfreetype6-dev libjpeg62-turbo-dev libonig-dev libzip-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/

RUN docker-php-ext-install -j$(nproc) pdo pdo_sqlite mbstring gd simplexml sockets zip

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY docker/prepend.ini /usr/local/etc/php/conf.d/prepend.ini
