Deploy:

    composer install
    php artisan migrate
    php artisan vendor:publish --provider="Uspdev\UspTheme\ServiceProvider" --tag=assets --force

Data:

    INSERT INTO printings (jobid, user, filename, copies, pages, printer, created_at, updated_at, status) VALUES (100, 'fulano', 'cv.odt', 10, 2, 'hp', '2020-02-01 10:00:00', '2020-02-01 10:00:00', 'Impresso');
