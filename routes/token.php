<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes: token
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware_guest'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/token', [
        'as' => 'token',
        'uses' => 'AuthenticatedSessionController@token',
    ]);
});
