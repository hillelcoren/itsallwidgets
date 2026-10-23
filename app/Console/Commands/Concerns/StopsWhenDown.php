<?php

namespace App\Console\Commands\Concerns;

use App\Support\Export\BusyMarkers;
use Illuminate\Support\Facades\Log;

/**
 * Keeps the itsallwidgets:* commands from writing while the new app copies
 * the data: while they run a marker in storage/framework/iaw-busy tells the
 * export manifest to wait, and they don't start while the site is down.
 *
 * The marker goes first and the down check second, so the new app never
 * sees the site down with nothing busy while a command is about to write:
 *
 *     $this->markBusy();
 *
 *     if ($this->skipWhenDown()) {
 *         $this->clearBusy();
 *
 *         return;
 *     }
 */
trait StopsWhenDown
{
    /**
     * True (after logging a line) when the site is down for maintenance.
     *
     * @return bool
     */
    protected function skipWhenDown()
    {
        if (! app()->isDownForMaintenance()) {
            return false;
        }

        $message = $this->getName() . ' skipped: the site is down for maintenance.';

        Log::info($message);

        if ($this->output) {
            $this->line($message);
        }

        return true;
    }

    /**
     * Write this command's busy marker (never throws).
     *
     * @return void
     */
    protected function markBusy()
    {
        BusyMarkers::write($this->getName());
    }

    /**
     * Remove this command's busy marker.
     *
     * @return void
     */
    protected function clearBusy()
    {
        BusyMarkers::clear($this->getName());
    }
}
