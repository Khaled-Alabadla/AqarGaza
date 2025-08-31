<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CorsDeleteMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', 'http://aqar-gaza.ct.ws')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-CSRF-TOKEN, Accept');
    }
}
