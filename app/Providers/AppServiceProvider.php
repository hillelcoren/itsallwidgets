<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FlutterApp;
use App\Models\PodcastEpisode;
use App\Observers\FlutterAppObserver;
use App\Observers\PodcastEpisodeObserver;
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
        PodcastEpisode::observe(PodcastEpisodeObserver::class);

        try {
            Cache::rememberForever('flutter-app-list', function () {
                return FlutterApp::approved()->latest()->get();
            });
            Cache::rememberForever('flutter-podcast-list', function () {
                return PodcastEpisode::visible()->orderBy('episode', 'desc')->orderBy('created_at', 'desc')->get();
            });
        } catch (\Illuminate\Database\QueryException $exception) {
            // this will fail when running composer install
            // before the database is migrated
        }
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
