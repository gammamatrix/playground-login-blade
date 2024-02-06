<?php
/*
|--------------------------------------------------------------------------
| Authentication Routes: confirm
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware_auth'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/confirm-password', [
        'as' => 'password.confirm',
        'uses' => 'ConfirmablePasswordController@show',
    ]);

    Route::post('/confirm-password', [
        'uses' => 'ConfirmablePasswordController@store',
    ]);
});
