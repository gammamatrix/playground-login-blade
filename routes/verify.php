<?php

declare(strict_types=1);
/*
|--------------------------------------------------------------------------
| Authentication Routes: verify
|--------------------------------------------------------------------------
|
|
*/

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => config('playground-login-blade.middleware.auth'),
    'namespace' => '\Playground\Login\Blade\Http\Controllers',
], function () {
    Route::get('/verify-email', [
        'as' => 'verification.notice',
        'uses' => 'EmailVerificationController@show',
    ]);

    Route::get('/verify-email/{id}/{hash}', [
        'as' => 'verification.verify',
        'uses' => 'EmailVerificationController@verify',
        'middleware' => ['signed', 'throttle:6,1'],
    ]);

    Route::post('/verify-email', [
        'as' => 'verification.send',
        'uses' => 'EmailVerificationController@send',
        'middleware' => ['throttle:6,1'],
    ]);
});
