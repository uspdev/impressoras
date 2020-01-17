<?php

return [

    'title' => env('APP_NAME'),
    'dashboard_url' => '/',

    'logout_url' => '/logout',

    'logout_method' => 'post',

    'login_url' => '/senhaunica/login',

    'menu' => [
        [
            'text' => 'Minhas Impressões',
            'url'  => '/printings',
            'can'  => 'logado'
        ],
        [
            'text' => 'Administrar impressões',
            'url'  => '/printings/admin',
            'can'  => 'admin'
        ],
        [
            'text' => 'Fila',
            'url'  => '/pendentes',
        ],
    ],

];
