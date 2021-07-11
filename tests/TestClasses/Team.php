<?php

namespace Koost89\LoginLinks\Tests\TestClasses;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Koost89\LoginLinks\Traits\CanLoginWithLink;

class Team extends Authenticatable
{
    use CanLoginWithLink;

    protected $fillable = ['email'];

    public $timestamps = false;

    public function getGuardName(): string
    {
        return 'team';
    }
}
