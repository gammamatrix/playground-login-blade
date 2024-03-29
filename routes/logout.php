<?php

declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Authentication Routes: logout
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware.default'),
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
