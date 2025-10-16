<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle($request, Closure $next)
    {
        if (!$request->isSecure() && env('FORCE_HTTPS', false)) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
