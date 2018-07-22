<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FlutterApp;
use App\Observers\FlutterAppObserver;
use Cache;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
    * Bootstrap any application services.
    *
    * @return void
    */
    public function boot()
    {
        FlutterApp::observe(FlutterAppObserver::class);

        try {
            Cache::rememberForever('flutter-app-list', function () {
                return FlutterApp::whereIsVisible(true)->latest()->get();
            });
        } catch (Exception $exception) {
            // this will fail when running composer install
            // befor the database is migrated
        }

        $tracking_id = config('services.analytics.tracking_id');
        if (auth()->check() && auth()->user()->isAdmin()) {
            $tracking_id = false;
        }
        View::share('tracking_id', $tracking_id);
    }

    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        //
    }
}
