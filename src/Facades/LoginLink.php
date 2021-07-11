<?php

namespace Koost89\LoginLinks\Facades;

use Illuminate\Support\Facades\Facade;

class LoginLink extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'login-link';
    }
}
