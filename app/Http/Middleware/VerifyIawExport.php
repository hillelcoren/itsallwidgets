<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Support\IawExportSignature;

class VerifyIawExport
{
    /**
     * The query parameters an export request may carry (digits only).
     *
     * @var array
     */
    protected $allowedQuery = ['after_id', 'since', 'ours'];

    /**
     * Only let signed export requests through. Every failure is a plain 404,
     * so the route gives nothing away.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // HTTPS only: the server passes HTTPS itself and no proxy is trusted.
        if (! $request->isSecure()) {
            abort(404);
        }

        $key = config('services.iaw_export.key');

        if (! is_string($key) || strlen($key) < 32) {
            abort(404);
        }

        $timestamp = (string) $request->headers->get('X-IAW-Timestamp', '');
        $nonce = (string) $request->headers->get('X-IAW-Nonce', '');
        $signature = (string) $request->headers->get('X-IAW-Signature', '');

        if (! preg_match('/^\d{10}$/', $timestamp)
            || ! preg_match('/^[A-Za-z0-9]{32}$/', $nonce)
            || ! preg_match('/^[0-9a-f]{64}$/', $signature)) {
            abort(404);
        }

        if (abs(time() - (int) $timestamp) > 300) {
            abort(404);
        }

        $canonical = IawExportSignature::canonical(
            $request->getMethod(),
            $request->getPathInfo(),
            (string) $request->server->get('QUERY_STRING', ''),
            $timestamp,
            $nonce,
            (string) $request->getContent()
        );

        if (! hash_equals(IawExportSignature::sign($canonical, $key), $signature)) {
            abort(404);
        }

        foreach ($request->query->all() as $name => $value) {
            if (! in_array($name, $this->allowedQuery, true)
                || ! is_string($value)
                || ! preg_match('/^\d{1,19}$/', $value)) {
                abort(404);
            }
        }

        // Cache TTLs are in minutes on Laravel 5.6.
        if (! Cache::add('iaw-export:' . $nonce, 1, 15)) {
            abort(404);
        }

        return $next($request);
    }
}
