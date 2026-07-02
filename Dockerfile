FROM uspdev/uspdev-php-apache:8.3

# packages for processing the printing
RUN sed -i 's|main|main non-free|' /etc/apt/sources.list.d/debian.sources && apt-get update && apt-get install -y \
    ghostscript \
    icc-profiles \
    poppler-utils \
    texlive-extra-utils \
    parallel \
    pdftk

RUN sed -i 's|/var/www/html|/var/www/html/public|' \
    /etc/apache2/sites-available/000-default.conf

USER www-data

COPY --chown=www-data . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

CMD ["apache2-foreground"]



