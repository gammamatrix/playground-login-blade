<?php

declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Authentication Routes: register
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware.guest'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/register', [
        'as' => 'register',
        'uses' => 'RegisteredUserController@create',
    ]);

    Route::post('/register', [
        'as' => 'register.post',
        'uses' => 'RegisteredUserController@store',
    ]);
});
