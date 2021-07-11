<?php


namespace Koost89\UserLogin\Traits;


trait CanLoginWithLink
{
    public function getGuardName(): string
    {
        return config('login-links.auth.guard');
    }
}
