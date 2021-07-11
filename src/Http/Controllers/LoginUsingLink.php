<?php

namespace Koost89\LoginLinks\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Koost89\LoginLinks\LoginLink;
use Koost89\LoginLinks\Models\LoginLinkToken;

class LoginUsingLink extends Controller
{
    public function __invoke(Request $request, LoginLink $loginLink): RedirectResponse
    {
        if ($loginLink->shouldExpireAfterVisit()) {
            $loginToken = LoginLinkToken::where('url', $request->getSchemeAndHttpHost() . $request->getRequestUri())
                ->firstOrFail();
        }

        $loginLink->login($request->auth_id, $request->auth_type, $loginToken ?? null);

        return redirect()->to(config('login-links.route.redirect_after_login'));
    }
}
