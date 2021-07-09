<?php


namespace Koost89\UserLogin\Tests\TestClasses;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['email'];

    public $timestamps = false;
}
