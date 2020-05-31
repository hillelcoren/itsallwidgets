<?php

namespace App\Observers;

use App\Models\FlutterStream;

class FlutterStreamObserver
{
    /**
     * Handle to the flutter stream "created" stream.
     *
     * @param  \Stream\FlutterStream  $flutterStream
     * @return void
     */
    public function created(FlutterStream $flutterStream)
    {
        cache()->forget('flutter-stream-list');
    }

    /**
     * Handle the flutter stream "updated" stream.
     *
     * @param  \Stream\FlutterStream  $flutterStream
     * @return void
     */
    public function updated(FlutterStream $flutterStream)
    {
        cache()->forget('flutter-stream-list');
    }

    /**
     * Handle the flutter stream "deleted" stream.
     *
     * @param  \Stream\FlutterStream  $flutterStream
     * @return void
     */
    public function deleted(FlutterStream $flutterStream)
    {
        cache()->forget('flutter-stream-list');
    }
}
