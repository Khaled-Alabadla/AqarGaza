<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConditionalPasswordConfirm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = Auth::user();

        // Bypass password confirmation for social login users
        if ($user && $user->provider) {
            return $next($request);
        }

        // Apply default password confirmation middleware for other users
        return app(RequirePassword::class)->handle($request, $next, $guard);
    }
}
