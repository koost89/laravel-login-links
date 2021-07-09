<?php

namespace Koost89\UserLogin;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\URL;
use Koost89\UserLogin\Models\UserLoginToken;

class UserLoginFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'user-login';
    }
}
