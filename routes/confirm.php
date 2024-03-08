<?php

declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Authentication Routes: confirm
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware.auth'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/confirm-password', [
        'as' => 'password.confirm',
        'uses' => 'ConfirmablePasswordController@show',
    ]);

    Route::post('/confirm-password', [
        'as' => 'password.confirmed',
        'uses' => 'ConfirmablePasswordController@store',
    ]);
});
