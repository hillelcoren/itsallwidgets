<?php

namespace App\Observers;

use App\Models\PodcastEpisode;

class PodcastEpisodeObserver
{
    /**
     * Handle to the flutter app "created" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function created(PodcastEpisode $episode)
    {
        cache()->forget('flutter-podcast-list');
    }

    /**
     * Handle the flutter app "updated" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function updated(PodcastEpisode $episode)
    {
        cache()->forget('flutter-podcast-list');
    }

    /**
     * Handle the flutter app "deleted" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function deleted(PodcastEpisode $episode)
    {
        cache()->forget('flutter-podcast-list');
    }
}
