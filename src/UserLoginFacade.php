<?php

namespace Koost89\UserLogin;

use Illuminate\Support\Facades\Facade;

class UserLoginFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'user-login';
    }
}
