ARG ENV
ARG TIMEZONE

FROM php:8.2.20-fpm
RUN apt-get update
RUN apt-get install -y \
    apt-utils \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    zlib1g-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    postgresql-server-dev-15

COPY config/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN docker-php-ext-install \
    intl \
    pdo_pgsql \
    zip \
    opcache

ARG ENV

RUN if [ "$ENV" = "dev" ] ; then \
        pecl install xdebug-3.2.1 && \
        docker-php-ext-enable xdebug  \
    ; fi

COPY config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY config/docker-php-memlimit.ini /usr/local/etc/php/conf.d/docker-php-memlimit.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

ARG TIMEZONE

RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && echo $TIMEZONE > /etc/timezone

WORKDIR /var/www/html

RUN usermod -u 1000 www-data