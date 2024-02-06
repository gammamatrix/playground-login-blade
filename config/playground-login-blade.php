<?php

return [
    'layout' => env('PLAYGROUND_LOGIN_BLADE_LAYOUT', env('PLAYGROUND_LAYOUT', 'playground::layouts.site')),
    'middleware' => ! is_string(env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE', 'web'))
        ? ''
        : array_map('trim', explode(
            ',',
            env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE', 'web')
        )),
    'middleware_auth' => ! is_string(env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_AUTH', 'web, auth'))
    ? ''
    : array_map('trim', explode(
        ',',
        env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_AUTH', 'web, auth')
    )),
    'middleware_guest' => ! is_string(env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_GUEST', 'web, guest'))
        ? ''
        : array_map('trim', explode(
            ',',
            env('PLAYGROUND_LOGIN_BLADE_MIDDLEWARE_GUEST', 'web, guest')
        )),
    'session' => (bool) env('PLAYGROUND_LOGIN_BLADE_SESSION', true),
    'view' => env('PLAYGROUND_LOGIN_BLADE_VIEW', env('PLAYGROUND_VIEW', 'playground-login::')),
    'load' => [
        'views' => (bool) env('PLAYGROUND_LOGIN_BLADE_LOAD_VIEWS', true),
        'routes' => (bool) env('PLAYGROUND_LOGIN_BLADE_LOAD_ROUTES', true),
    ],
    'routes' => [
        'confirm' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_RESET', true),
        'forgot' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_FORGOT', true),
        'login' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_LOGIN', true),
        'logout' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_LOGOUT', true),
        'register' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_REGISTER', true),
        'reset' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_RESET', true),
        'token' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_TOKEN', true),
        'verify' => (bool) env('PLAYGROUND_LOGIN_BLADE_ROUTES_RESET', true),
    ],
    'sitemap' => [
        'enable' => (bool) env('PLAYGROUND_LOGIN_BLADE_SITEMAP_ENABLE', true),
        'guest' => (bool) env('PLAYGROUND_LOGIN_BLADE_SITEMAP_GUEST', true),
        'user' => (bool) env('PLAYGROUND_LOGIN_BLADE_SITEMAP_USER', true),
        'view' => env('PLAYGROUND_LOGIN_BLADE_SITEMAP_VIEW', 'playground-login::sitemap'),
    ],
];
