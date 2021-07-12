<?php


namespace Koost89\LoginLinks\Traits;

use Koost89\LoginLinks\Facades\LoginLink;

trait CanLoginWithLink
{
    public function getGuardName(): string
    {
        return config('login-links.auth.guard');
    }

    public function hasVisitLimit(): bool
    {
        return $this->getAllowedVisits() > 0;
    }

    public function getAllowedVisits()
    {
        return config('login-links.route.allowed_visits_before_expiration');
    }

    public function generateLoginLink(): string
    {
        return LoginLink::generate($this);
    }
}
