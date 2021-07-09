<?php

namespace Koost89\UserLogin\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Koost89\UserLogin\UserLogin;

class LoginUsingLink extends Controller
{
    public function __invoke(Request $request, UserLogin $otlService): RedirectResponse
    {
        if ($otlService->shouldExpireAfterVisit()) {
            $otlService->ensureUserLoginTokenExists($request->getSchemeAndHttpHost() . $request->getRequestUri());
        }

        $otlService->login($request->auth_id);

        return redirect()->to(config('otl.route.redirect_after_login'));
    }
}
