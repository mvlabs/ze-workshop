FROM php:fpm

RUN apt-get update && apt-get install libpq-dev libicu-dev -y
RUN docker-php-ext-install pdo pgsql pdo_pgsql intl zip bcmath