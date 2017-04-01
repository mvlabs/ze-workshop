FROM php:fpm-alpine

RUN apk --no-cache --update add postgresql-dev icu-dev
RUN docker-php-ext-install pdo pgsql pdo_pgsql intl zip bcmath