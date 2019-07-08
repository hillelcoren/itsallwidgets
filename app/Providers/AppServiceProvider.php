<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\FlutterApp;
use App\Models\FlutterEvent;
use App\Models\PodcastEpisode;
use App\Models\FlutterArtifact;
use App\Observers\FlutterAppObserver;
use App\Observers\FlutterEventObserver;
use App\Observers\PodcastEpisodeObserver;
use Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;

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
        FlutterArtifact::observe(FlutterArtifactObserver::class);
        FlutterEvent::observe(FlutterEventObserver::class);
        PodcastEpisode::observe(PodcastEpisodeObserver::class);

        try {
            if (! cache('flutter-app-list')) {
                Cache::rememberForever('flutter-app-list', function () {
                    return FlutterApp::approved()->latest()->get();
                });
            }
            if (! cache('flutter-artifact-list')) {
                Cache::rememberForever('flutter-artifact-list', function () {
                    return FlutterArtifact::approved()->latest()->get();
                });
            }
            if (! cache('flutter-event-list')) {
                Cache::rememberForever('flutter-event-list', function () {
                    return FlutterEvent::approved()->latest()->get();
                });
            }
            if (! cache('flutter-podcast-list')) {
                Cache::rememberForever('flutter-podcast-list', function () {
                    return PodcastEpisode::uploaded()->orderBy('episode', 'desc')->get();
                });
            }
            if (! cache('flutter-featured-podcast-list')) {
                Cache::rememberForever('flutter-featured-podcast-list', function () {
                    return PodcastEpisode::uploaded()->where('is_featured', true)->orderBy('episode', 'desc')->get();
                });
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            // this will fail when running composer install
            // before the database is migrated
        }

        $ip = \Request::getClientIp();
        if (!cache()->has($ip . '_latitude')) {
            $link = 'http://www.geoplugin.net/php.gp?ip=' . $ip;
            $latitude = 0;
            $longitude = 0;
            if ($data = unserialize(@file_get_contents($link))) {
                $latitude = floatval($data['geoplugin_latitude']);
                $longitude = floatval($data['geoplugin_longitude']);
            }
            if ($latitude && $longitude) {
                cache([$ip . '_latitude' => $latitude], 60 * 60 * 24);
                cache([$ip . '_longitude' => $longitude], 60 * 60 * 24);
            }
        }
    }

    /**
    * Register any application services.
    *
    * @return void
    */
    public function register()
    {
        Blade::component('components.navigation', 'navigation');
        Blade::component('components.channels', 'channels');
    }
}
