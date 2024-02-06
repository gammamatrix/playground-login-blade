<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes: reset
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware_guest'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/reset-password/{token}', [
        'as' => 'password.reset',
        'uses' => 'NewPasswordController@create',
    ]);

    Route::post('/reset-password', [
        'as' => 'password.update',
        'uses' => 'NewPasswordController@store',
    ]);
});
