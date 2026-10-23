<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * Maintenance put on by the new app is tagged ("iaw") and carries a lease
     * ("iaw_until"): once it has passed the site comes back up by itself.
     * Maintenance put on by hand is never lifted here. The export routes keep
     * answering on the legacy host while the site is down, so the final copy
     * can run.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $downFile = $this->app->storagePath() . '/framework/down';

        if (is_file($downFile)) {
            $data = json_decode((string) @file_get_contents($downFile), true);

            if (is_array($data)
                && isset($data['iaw']) && $data['iaw'] === true
                && isset($data['iaw_until']) && is_int($data['iaw_until']) && $data['iaw_until'] < time()) {
                @unlink($downFile);
            }
        }

        if ($request->is('iaw-export/*') && $request->getHost() === config('services.iaw_export.host')) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
