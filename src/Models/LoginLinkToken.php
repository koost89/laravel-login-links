<?php


namespace Koost89\UserLogin\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLinkToken extends Model
{
    protected $fillable = ['url'];
}
