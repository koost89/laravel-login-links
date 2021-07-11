<?php


namespace Koost89\LoginLinks\Traits;

use Koost89\LoginLinks\Facades\LoginLink;

trait CanLoginWithLink
{
    public function getGuardName(): string
    {
        return config('login-links.auth.guard');
    }

    public function generateLoginLink()
    {
        return LoginLink::generate($this);
    }
}
