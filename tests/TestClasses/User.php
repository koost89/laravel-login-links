<?php


namespace Koost89\UserLogin\Tests\TestClasses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Koost89\UserLogin\Database\Factories\UserFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['email'];

    public $timestamps = false;

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
