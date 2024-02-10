<?php

return [
    'layout' => env('PLAYGROUND_LOGIN_BLADE_LAYOUT', env('PLAYGROUND_BLADE_LAYOUT', 'playground::layouts.site')),
    'load' => [
        'views' => (bool) env('PLAYGROUND_LOGIN_BLADE_LOAD_VIEWS', true),
        'routes' => (bool) env('PLAYGROUND_LOGIN_BLADE_LOAD_ROUTES', true),
    ],
    'middleware' => [
        'auth' => env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_AUTH', ['web', 'auth']),
        'default' => env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_DEFAULT', 'web'),
        'guest' => env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_GUEST', ['web', 'guest']),
    ],
    'routes' => [
        'confirm' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_CONFIRM', true),
        'forgot' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_FORGOT', true),
        'login' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_LOGIN', true),
        'logout' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_LOGOUT', true),
        'register' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_REGISTER', true),
        'reset' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_RESET', true),
        'token' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_TOKEN', true),
        'verify' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_VERIFY', true),
    ],
    'session' => (bool) env('PLAYGROUND_LOGIN_BLADE_SESSION', true),
    'sitemap' => [
        'enable' => (bool) env('PLAYGROUND_LOGIN_BLADE_SITEMAP_ENABLE', true),
        'guest' => (bool) env('PLAYGROUND_LOGIN_BLADE_SITEMAP_GUEST', true),
        'user' => (bool) env('PLAYGROUND_LOGIN_BLADE_SITEMAP_USER', true),
        'view' => env('PLAYGROUND_LOGIN_BLADE_SITEMAP_VIEW', 'playground-login::sitemap'),
    ],
    'view' => env('PLAYGROUND_LOGIN_BLADE_VIEW', 'playground-login::'),
];
