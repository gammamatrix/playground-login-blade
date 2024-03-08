<?php

declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Authentication Routes: forgot
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware.guest'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/forgot-password', [
        'as' => 'password.request',
        'uses' => 'PasswordResetLinkController@create',
    ]);

    Route::post('/forgot-password', [
        'as' => 'password.email',
        'uses' => 'PasswordResetLinkController@store',
    ]);
});
