<?php

namespace Koost89\LoginLinks;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Koost89\LoginLinks\Events\LoginLinkGenerated;
use Koost89\LoginLinks\Events\LoginLinkUsed;
use Koost89\LoginLinks\Helpers\URLHelper;
use Koost89\LoginLinks\Models\LoginLinkToken;

class LoginLink
{
    public function generate(Authenticatable $authenticatable): string
    {
        $url = $this->generateUrl([
            'auth_id' => $authenticatable->getAuthIdentifier(),
            'auth_type' => URLHelper::classToEncodedString(get_class($authenticatable)),
        ]);

        $this->handleTokenCreation($url, $authenticatable->getAllowedVisits());

        LoginLinkGenerated::dispatch($authenticatable->id, get_class($authenticatable));

        return $url;
    }

    public function login($authId, string $class, LoginLinkToken $loginLinkToken): void
    {
        $authenticatable = new $class;
        $guard = $authenticatable->getGuardName();

        if (method_exists(Auth::guard($guard), 'login')) {
            Auth::guard($guard)
                ->loginUsingId($authId, config('login-links.auth.remember'));

            LoginLinkUsed::dispatch($authId, $class);
        }

        if ($authenticatable->hasVisitLimit()) {
            $this->handleTokenVisits($loginLinkToken);
        }
    }

    protected function handleTokenCreation($url, $visits_allowed): void
    {
        LoginLinkToken::create([
            'url' => $url,
            'visits_allowed' => $visits_allowed,
        ]);
    }

    protected function handleTokenVisits(LoginLinkToken $loginLinkToken): ?bool
    {
        if (! $loginLinkToken) {
            return true;
        }

        $loginLinkToken->visits++;

        if ($loginLinkToken->hasExceededVisitLimit()) {
            return $loginLinkToken->delete();
        }

        return $loginLinkToken->save();
    }

    protected function generateUrl(array $params): string
    {
        return URL::temporarySignedRoute('login-links.login', $this->createExpiration(), $params);
    }

    protected function createExpiration(): Carbon
    {
        return now()->addSeconds(config('login-links.route.expiration'));
    }
}
