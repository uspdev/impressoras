FROM php:7.4-apache

# packages
RUN sed -i 's|main|main non-free|' /etc/apt/sources.list && apt-get update && apt-get install -y \
    freetds-bin \
    freetds-dev \
    ghostscript \
    icc-profiles \
    libgs9-common \
    poppler-utils \
    texlive-extra-utils \
    libicu-dev \
    libzip-dev \
    git \
    zip \
    unzip

# cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# hack for sybase libs @ debian 11
RUN cp /usr/lib/x86_64-linux-gnu/libsybdb.so.5 /usr/lib/libsybdb.so

# php libs
RUN docker-php-ext-install \
    intl \
    pdo_dblib \
    pdo_mysql \
    zip

# laravel
COPY . .
RUN chown -R www-data: /var/www
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

USER www-data
RUN composer install --no-interaction --no-dev

CMD ["./serve.sh"]

# source:
# [1] https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04
# [2] https://github.com/docker-library/php
