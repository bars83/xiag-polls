FROM php:7.3-fpm

ENV PHPREDIS_VERSION 3.0.0

RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libfcgi0ldbl \
    && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
        composer --version

COPY ./ping.conf /usr/local/etc/php-fpm.d/ping.conf

HEALTHCHECK --interval=10s --timeout=3s \
    CMD \
    SCRIPT_NAME=/ping \
    SCRIPT_FILENAME=/ping \
    REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

RUN usermod -u 1000 www-data

USER www-data

CMD composer install --dev --no-interaction && php-fpm