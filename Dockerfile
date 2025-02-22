FROM dunglas/frankenphp:php8.2.27

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . /app

RUN apt update && apt install zip libzip-dev default-mysql-client -y && \
    docker-php-ext-install zip pdo_mysql && \
    docker-php-ext-enable zip pdo_mysql

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install
