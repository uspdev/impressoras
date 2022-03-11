<?php

$right_menu = [
    [
        'text' => '<i class="fas fa-hard-hat"></i>',
        'title' => 'Logs',
        'target' => '_blank',
        'url' => config('app.url').'/logs',
        'align' => 'right',
        'can' => 'admin',
    ],
];

return [
    'title' => config('app.name'),

    // USP_THEME_SKIN deve ser colocado no .env da aplicação
    'skin' => env('USP_THEME_SKIN', 'uspdev'),

    // chave da sessão. Troque em caso de colisão com outra variável de sessão.
    'session_key' => 'laravel-usp-theme',

    // usado na tag base, permite usar caminhos relativos nos menus e demais elementos html
    // na versão 1 era dashboard_url
    'app_url' => config('app.url'),

    // login e logout
    'logout_method' => 'POST',
    'logout_url' => 'logout',
    'login_url' => 'login',

    'menu' => [
        [
            'text' => 'Minhas Impressões',
            'url' => '/printings',
            'can' => 'logado',
        ],
        [
            'text' => 'Impressoras',
            'url' => '/printers',
            'can' => '',
        ],
        [
            'text' => 'Regras',
            'url' => '/rules',
            'can' => 'admin',
        ],
    ],
    'right_menu' => $right_menu,
];
