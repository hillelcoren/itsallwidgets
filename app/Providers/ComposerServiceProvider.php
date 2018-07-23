<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $tracking_id = config('services.analytics.tracking_id');

            if (auth()->check() && auth()->user()->is_admin) {
                $tracking_id = false;
            }

            $view->with('tracking_id', $tracking_id);
        });

        /*
        View::composer(
            '', 'App\Http\ViewComposers\ProfileComposer'
        );
        */
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
