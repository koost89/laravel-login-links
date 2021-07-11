<?php

namespace Koost89\UserLogin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Koost89\UserLogin\LoginLink;

class LoginUsingLink extends Controller
{
    public function __invoke(Request $request, LoginLink $otlService): RedirectResponse
    {
        if ($otlService->shouldExpireAfterVisit()) {
            $otlService->ensureUserLoginTokenExists($request->getSchemeAndHttpHost() . $request->getRequestUri());
        }

        $otlService->login($request->auth_id, $request->auth_type);

        return redirect()->to(config('login-links.route.redirect_after_login'));
    }
}
