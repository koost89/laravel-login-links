<?php

namespace Koost89\UserLogin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasValidSignature
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->hasValidSignature(), config('login-links.auth.invalid_signature_response'));

        return $next($request);
    }
}
