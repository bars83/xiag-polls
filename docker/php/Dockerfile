FROM php:7.3-fpm

RUN apt-get update && \
    apt-get install -y \
        git \
        unzip \
        libzip-dev \
        libfcgi0ldbl \
    && \
    rm -rf /var/lib/apt/lists/*

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

CMD php-fpm