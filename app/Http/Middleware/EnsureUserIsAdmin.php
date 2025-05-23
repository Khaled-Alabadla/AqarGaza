<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->super_admin) {
            return $next($request);
        }
        if (!in_array(Auth::user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
