<?php

namespace Koost89\UserLogin;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Koost89\UserLogin\Models\UserLoginToken;

class LoginLink
{
    private $currentUserLoginToken;

    public function create($id, array $extraParams = []): string
    {
        $model = $this->getUser($id);

        $url = $this->generateUrl(array_merge($extraParams, ['auth_id' => $model->getAuthIdentifier()]));

        if ($this->shouldExpireAfterVisit()) {
            UserLoginToken::create(['url' => $url]);
        }

        return $url;
    }

    public function login($auth_id)
    {
        $guard = config('login-links.auth.guard');

        if (method_exists(Auth::guard($guard), 'login')) {
            Auth::guard($guard)
                ->login($this->getUser($auth_id), config('login-links.auth.remember'));
        }

        if ($this->shouldExpireAfterVisit()) {
            $this->deleteCurrentUserLoginToken();
        }
    }

    public function deleteCurrentUserLoginToken(): void
    {
        if (! $this->currentUserLoginToken) {
            throw new \LogicException("UserLoginToken is not available in service");
        }

        $this->currentUserLoginToken->delete();
    }

    public function getUser($id): Authenticatable
    {
        $model = Auth::guard(config('login-links.auth.guard'))
            ->getProvider()
            ->retrieveById($id);

        if (! $model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    public function ensureUserLoginTokenExists(string $url): void
    {
        $this->currentUserLoginToken = UserLoginToken::where('url', $url)->firstOrFail();
    }

    public function getExpiration(): Carbon
    {
        return now()->addSeconds(config('login-links.route.expiration'));
    }

    public function shouldExpireAfterVisit()
    {
        return config('login-links.route.expire_after_visit');
    }

    protected function generateUrl(array $params): string
    {
        return URL::temporarySignedRoute('login-links.login', $this->getExpiration(), $params);
    }
}
