<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes: login
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware_guest'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/login', [
        'as' => 'login',
        'uses' => 'AuthenticatedSessionController@create',
    ]);

    Route::post('/login', [
        'as' => 'login.post',
        'uses' => 'AuthenticatedSessionController@store',
    ]);
});
