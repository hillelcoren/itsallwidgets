<?php

namespace App\Observers;

use App\Models\FlutterApp;

class FlutterArtifactObserver
{
    /**
     * Handle to the flutter app "created" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function created(FlutterArtifact $flutterArtifact)
    {
        cache()->forget('flutter-artifact-list');
    }

    /**
     * Handle the flutter app "updated" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function updated(FlutterArtifact $flutterArtifact)
    {
        cache()->forget('flutter-artifact-list');
    }

    /**
     * Handle the flutter app "deleted" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function deleted(FlutterArtifact $flutterArtifact)
    {
        cache()->forget('flutter-artifact-list');
    }
}
