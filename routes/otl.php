<?php

use Illuminate\Support\Facades\Route;
use \Koost89\UserLogin\Http;

Route::get(config('otl.route.path', '/otl'), Http\Controllers\LoginUsingLink::class)
    ->middleware([Http\Middleware\HasValidSignature::class, 'web'])
    ->name('otl.login');
