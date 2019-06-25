<?php

namespace App\Observers;

use App\Models\FlutterEvent;

class FlutterEventObserver
{
    /**
     * Handle to the flutter event "created" event.
     *
     * @param  \Event\FlutterEvent  $flutterEvent
     * @return void
     */
    public function created(FlutterEvent $flutterEvent)
    {
        cache()->forget('flutter-event-list');
    }

    /**
     * Handle the flutter event "updated" event.
     *
     * @param  \Event\FlutterEvent  $flutterEvent
     * @return void
     */
    public function updated(FlutterEvent $flutterEvent)
    {
        cache()->forget('flutter-event-list');
    }

    /**
     * Handle the flutter event "deleted" event.
     *
     * @param  \Event\FlutterEvent  $flutterEvent
     * @return void
     */
    public function deleted(FlutterEvent $flutterEvent)
    {
        cache()->forget('flutter-event-list');
    }
}
