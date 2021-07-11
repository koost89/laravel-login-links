<?php

namespace Koost89\LoginLinks\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Koost89\LoginLinks\Helpers\URLHelper;
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

        $class = URLHelper::encodedStringToClass($request->auth_type);

        $loginLink->login($request->auth_id, $class, $loginToken ?? null);

        return redirect()->to(config('login-links.route.redirect_after_login'));
    }
}
