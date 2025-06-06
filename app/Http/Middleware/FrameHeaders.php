<?php

namespace App\Http\Middleware;

use Closure;

class FrameHeaders
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'self' https://flutterpro.dev");
        return $response;
    }
}
