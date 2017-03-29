FROM php:7.1-fpm

RUN apt-get update; apt-get install libpq-dev libicu-dev -y
RUN apt-get install git -y
RUN docker-php-ext-install pgsql
RUN docker-php-ext-install pdo_pgsql
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install intl
RUN docker-php-ext-install zip
RUN docker-php-ext-install bcmath

COPY docker/php/global-config/ /usr/local/etc/
# Copy local php configuration
COPY docker/php/config/ /usr/local/etc/pool.d/
# Copy local source contents
COPY . /app/

# Change data ownership
RUN chown -R www-data:www-data /app/*

# Change data permissions
RUN chmod -R +r /app/*