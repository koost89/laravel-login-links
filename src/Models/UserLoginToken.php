<?php


namespace Koost89\UserLogin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Koost89\UserLogin\Database\Factories\UserLoginTokenFactory;

class UserLoginToken extends Model
{
    use HasFactory;

    protected $fillable = ['url'];

    protected static function newFactory(): UserLoginTokenFactory
    {
        return UserLoginTokenFactory::new();
    }

}
