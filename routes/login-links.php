<?php

use Illuminate\Support\Facades\Route;
use \Koost89\UserLogin\Http;

Route::get(config('login-links.route.path'), Http\Controllers\LoginUsingLink::class)
    ->middleware([Http\Middleware\HasValidSignature::class, 'web'])
    ->name('login-links.login');
