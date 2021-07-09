<?php

namespace Koost89\UserLogin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasValidSignature
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->hasValidSignature(), config('otl.auth.invalid_signature_response', 403));

        return $next($request);
    }
}
