<?php


namespace Koost89\LoginLinks\Traits;

trait CanLoginWithLink
{
    public function getGuardName(): string
    {
        return config('login-links.auth.guard');
    }
}
