<?php

namespace App\Observers;

use App\Models\FlutterApp;

class FlutterAppObserver
{
    /**
     * Handle to the flutter app "created" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function created(FlutterApp $flutterApp)
    {
        cache()->forget('flutter-app-list');
    }

    /**
     * Handle the flutter app "updated" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function updated(FlutterApp $flutterApp)
    {
        cache()->forget('flutter-app-list');
    }

    /**
     * Handle the flutter app "deleted" event.
     *
     * @param  \App\FlutterApp  $flutterApp
     * @return void
     */
    public function deleted(FlutterApp $flutterApp)
    {
        cache()->forget('flutter-app-list');
    }
}
