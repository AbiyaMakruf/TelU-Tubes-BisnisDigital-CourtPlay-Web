<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Jika user sudah login dan mencoba buka route guest (login/signup/about/...)
     * maka redirect ke /analytics.
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect()->route('analytics'); // ubah dari login → analytics
            }
        }

        return $next($request);
    }
}
