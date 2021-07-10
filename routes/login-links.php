<?php

use Illuminate\Support\Facades\Route;
use \Koost89\UserLogin\Http;

Route::get(config('login-links.route.path'), Http\Controllers\LoginUsingLink::class)
    ->middleware(
        array_merge(
            config('login-links.route.additional_middleware'),
            ['signed', 'web']
        )
    )
    ->name('login-links.login');
