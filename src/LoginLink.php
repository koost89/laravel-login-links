<?php

namespace Koost89\UserLogin;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Koost89\UserLogin\Models\UserLoginToken;

class LoginLink
{
    private $currentLoginToken;
    private $authenticatable;

    public function create($authenticatable): string
    {
        $this->authenticatable = $authenticatable;

        $url = $this->generateUrl([
            'auth_id' => $authenticatable->getAuthIdentifier(),
            'auth_type' => $this->toTypeString(),
        ]);

        if ($this->shouldExpireAfterVisit()) {
            UserLoginToken::create(['url' => $url]);
        }

        return $url;
    }

    public function login($authId, $authType)
    {
        $guard = $this->getGuardFromAuthType($authType);

        if (method_exists(Auth::guard($guard), 'login')) {
            Auth::guard($guard)
                ->loginUsingId($authId, config('login-links.auth.remember'));
        }

        if ($this->shouldExpireAfterVisit()) {
            $this->deleteCurrentUserLoginToken();
        }
    }

    public function deleteCurrentUserLoginToken(): void
    {
        if (! $this->currentLoginToken) {
            throw new \LogicException("UserLoginToken is not available in service");
        }

        $this->currentLoginToken->delete();
    }

    public function getUser($authId, $guard): Authenticatable
    {
        $model = Auth::guard($guard)
            ->getProvider()
            ->retrieveById($authId);

        if (! $model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    public function ensureUserLoginTokenExists(string $url): void
    {
        $this->currentLoginToken = UserLoginToken::where('url', $url)->firstOrFail();
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

    private function toTypeString(): string
    {
        return  base64_encode(
            Str::of(get_class($this->authenticatable))
                ->replace('\\', '_')
        );
    }

    private function fromTypeString($string): string
    {
        return Str::of(base64_decode($string))
            ->replace('_', '\\');
    }

    private function getGuardFromAuthType($authType)
    {
        $class = $this->fromTypeString($authType);

        return (new $class)->getGuardName();
    }
}
