<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Models\PodcastEpisode;
use App\Models\FlutterArtifact;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('flutter_artifact', function($value) {
            return \App\Models\FlutterArtifact::where('slug', $value)->first();
        });

        Route::bind('flutter_app', function($value) {
            return \App\Models\FlutterApp::where('slug', $value)->first();
        });

        Route::bind('flutter_event', function($value) {
            return \App\Models\FlutterEvent::where('slug', $value)->first();
        });

        Route::bind('flutter_stream', function($value) {
            return \App\Models\FlutterStream::where('slug', $value)->first();
        });

        Route::bind('episode_id', function($value) {
            return \App\Models\PodcastEpisode::where('id', $value)->firstOrFail();
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapExportRoutes();

        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the signed export routes the new app copies its data through.
     *
     * They only answer on the legacy host, carry no session or cookies and
     * are loaded first so no other route can shadow them.
     *
     * @return void
     */
    protected function mapExportRoutes()
    {
        Route::domain(config('services.iaw_export.host'))
             ->prefix('iaw-export')
             ->namespace($this->namespace)
             ->middleware(['iaw.export', 'throttle:3000,1'])
             ->group(base_path('routes/export.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
