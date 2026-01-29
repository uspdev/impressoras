FROM php:8.3-apache

# packages
RUN sed -i 's|main|main non-free|' /etc/apt/sources.list.d/debian.sources && apt-get update && apt-get install -y \
    freetds-bin \
    freetds-dev \
    ghostscript \
    icc-profiles \
    poppler-utils \
    texlive-extra-utils \
    parallel \
    pdftk \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    git \
    zip \
    unzip

# cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# php libs
RUN docker-php-ext-install \
    intl \
    pdo_dblib \
    pdo_mysql \
    soap \
    zip

# php memory
ENV PHP_MEMORY_LIMIT 512M
ENV PHP_UPLOAD_LIMIT 512M
RUN { \
        echo 'memory_limit=${PHP_MEMORY_LIMIT}'; \
        echo 'upload_max_filesize=${PHP_UPLOAD_LIMIT}'; \
        echo 'post_max_size=${PHP_UPLOAD_LIMIT}'; \
    } > "${PHP_INI_DIR}/conf.d/upload.ini"

# apache
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|' /etc/apache2/sites-available/000-default.conf

# composer cache
COPY composer.json composer.lock ./
USER www-data
RUN composer install --no-interaction --no-dev --no-autoloader

# impressoras
USER root
COPY --chown=www-data . .
USER www-data
RUN composer dump-autoload

CMD ["./serve.sh"]

# source:
# [1] https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04
# [2] https://github.com/docker-library/php
# [3] https://github.com/nextcloud/docker/blob/master/29/fpm-alpine/Dockerfile
