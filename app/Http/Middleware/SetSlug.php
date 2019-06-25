<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\FlutterEvent;

class SetSlug
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure  $next
    * @param  string|null  $guard
    * @return mixed
    */
    public function handle($request, Closure $next)
    {
        $request_array = $request->all();

        if (isset($request_array['title'])) {
            $request_array['slug'] = str_slug($request_array['title']);
            $request->replace($request_array);
        } elseif (isset($request_array['event_name'])) {
            $slug = str_slug($request_array['event_name'];
            $count = 1;

            if (FlutterEvent::whereSlug($slug)->first()) {
                while(FlutterEvent::whereSlug($slug . '-' . $count)->first()) {
                    $count++;
                }

                $slug .= '-' . $count;
            }

            $request_array['slug'] = $slug;
            $request->replace($request_array);
        }

        return $next($request);
    }
}
