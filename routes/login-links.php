<?php

use Illuminate\Support\Facades\Route;
use Koost89\LoginLinks\Http;

Route::get(config('login-links.route.path'), \Koost89\LoginLinks\Http\Controllers\LoginUsingLink::class)
    ->middleware(
        array_merge(
            config('login-links.route.additional_middleware'),
            ['signed', 'web']
        )
    )
    ->name('login-links.login');
