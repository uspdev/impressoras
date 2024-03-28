#! /bin/bash

# resolve problemas de configuração
php artisan config:cache

# command padrão do php-apache
# ver: https://github.com/docker-library/php
apache2-foreground
