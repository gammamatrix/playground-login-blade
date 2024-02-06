<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes: forgot
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware_guest'),
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
