<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FlutterApp;
use App\Observers\FlutterAppObserver;
use Cache;

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

        Cache::rememberForever('flutter-app-list', function () {
            return FlutterApp::whereIsVisible(true)->latest()->get();
        });

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
