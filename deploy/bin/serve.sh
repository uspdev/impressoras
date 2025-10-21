#! /bin/bash

# publica os assets
php artisan vendor:publish --provider='Uspdev\UspTheme\ServiceProvider' --tag=assets --force

# resolve problemas de configuração
php artisan config:cache

# command padrão do php-apache
apache2-foreground
