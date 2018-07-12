<?php

namespace App\Http\Middleware;

use Closure;

class CheckPassword
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (! session('is_authorized')) {
            return redirect('/check_password');
        }

        return $next($request);
    }

}
