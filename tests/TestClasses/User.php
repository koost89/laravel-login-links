<?php

namespace Koost89\UserLogin\Tests\TestClasses;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Koost89\UserLogin\Traits\CanLoginWithLink;

class User extends Authenticatable
{
    use CanLoginWithLink;

    protected $fillable = ['email'];

    public $timestamps = false;
}
