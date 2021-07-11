<?php

namespace Koost89\LoginLinks;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Koost89\LoginLinks\Events\LoginLinkGenerated;
use Koost89\LoginLinks\Events\LoginLinkUsed;
use Koost89\LoginLinks\Helpers\URLHelper;
use Koost89\LoginLinks\Models\LoginLinkToken;

class LoginLink
{
    public function generate($authenticatable): string
    {
        $url = $this->generateUrl([
            'auth_id' => $authenticatable->getAuthIdentifier(),
            'auth_type' => URLHelper::classToEncodedString(get_class($authenticatable)),
        ]);

        if ($this->shouldExpireAfterVisit()) {
            LoginLinkToken::create(['url' => $url]);
        }

        LoginLinkGenerated::dispatch($authenticatable->id, get_class($authenticatable));

        return $url;
    }

    public function login($authId, $class, $userLoginToken = null)
    {
        $guard = (new $class)->getGuardName();

        if (method_exists(Auth::guard($guard), 'login')) {
            Auth::guard($guard)
                ->loginUsingId($authId, config('login-links.auth.remember'));

            LoginLinkUsed::dispatch($authId, $class);
        }

        if ($this->shouldExpireAfterVisit()) {
            $this->deleteLoginToken($userLoginToken);
        }
    }

    public function shouldExpireAfterVisit()
    {
        return config('login-links.route.expire_after_visit');
    }

    protected function generateUrl(array $params): string
    {
        return URL::temporarySignedRoute('login-links.login', $this->createExpiration(), $params);
    }

    protected function getGuardFromClass($class)
    {
        return (new $class)->getGuardName();
    }

    protected function deleteLoginToken($userLoginToken): void
    {
        $userLoginToken->delete();
    }

    protected function createExpiration(): Carbon
    {
        return now()->addSeconds(config('login-links.route.expiration'));
    }
}
