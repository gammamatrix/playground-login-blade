<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

if (! empty(config('playground-login-blade.routes.logout'))) {
    Route::group([
        'middleware' => config('playground-login-blade.middleware'),
        'namespace' => '\Playground\Login\Blade\Http\Controllers',
    ], function () {
        Route::post('/logout', [
            'uses' => 'AuthenticatedSessionController@destroy',
        ]);

        Route::get('/logout', [
            'as' => 'logout',
            'uses' => 'AuthenticatedSessionController@destroy',
        ]);
    });
}

Route::group([
    'middleware' => config('playground-login-blade.middleware_guest'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    if (! empty(config('playground-login-blade.routes.token'))) {
        Route::get('/token', [
            'as' => 'token',
            'uses' => 'AuthenticatedSessionController@token',
        ]);
    }

    if (! empty(config('playground-login-blade.routes.login'))) {
        Route::get('/login', [
            'as' => 'login',
            'uses' => 'AuthenticatedSessionController@create',
        ]);

        Route::post('/login', [
            'as' => 'login.post',
            'uses' => 'AuthenticatedSessionController@store',
        ]);
    }

    if (! empty(config('playground-login-blade.routes.register'))) {
        Route::get('/register', [
            'as' => 'register',
            'uses' => 'RegisteredUserController@create',
        ]);

        Route::post('/register', [
            'as' => 'register.post',
            'uses' => 'RegisteredUserController@store',
        ]);
    }

    if (! empty(config('playground-login-blade.routes.forgot'))) {
        Route::get('/forgot-password', [
            'as' => 'password.request',
            'uses' => 'PasswordResetLinkController@create',
        ]);

        Route::post('/forgot-password', [
            'as' => 'password.email',
            'uses' => 'PasswordResetLinkController@store',
        ]);
    }

    if (! empty(config('playground-login-blade.routes.reset'))) {
        Route::get('/reset-password/{token}', [
            'as' => 'password.reset',
            'uses' => 'NewPasswordController@create',
        ]);

        Route::post('/reset-password', [
            'as' => 'password.update',
            'uses' => 'NewPasswordController@store',
        ]);
    }
});
