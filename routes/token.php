<?php

declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Authentication Routes: token
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware.default'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/token', [
        'as' => 'token',
        'uses' => 'AuthenticatedSessionController@token',
    ]);
});
