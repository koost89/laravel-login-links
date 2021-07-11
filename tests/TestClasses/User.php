<?php

namespace Koost89\LoginLinks\Tests\TestClasses;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Koost89\LoginLinks\Traits\CanLoginWithLink;

class User extends Authenticatable
{
    use CanLoginWithLink;

    protected $fillable = ['email'];

    public $timestamps = false;
}
