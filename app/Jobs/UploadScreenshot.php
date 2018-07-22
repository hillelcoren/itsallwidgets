<?php

namespace App\Jobs;

use Image;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadScreenshot implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    protected $app;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->file = request()->file('screenshot');

        if (! $this->file) {
            return;
        }

        $filename = 'app-' . $this->app->id . '.' . $this->file->extension();
        $this->file->move(public_path('/screenshots'), $filename);

        // check for an error border
        $image = Image::make(public_path('/screenshots') . '/' . $filename);

        if ($this->isErrorPixel($image, 0, 500)
            && ! $this->isErrorPixel($image, 50, 500)
        ) {
            session()->flash('warning', 'We\'ve detected a yellow border around the image, there may have been an error when the screenshot was taken.');
        }

        // check for gif

    }

    private function isErrorPixel($image, $x, $y)
    {
        $hex = $image->pickColor($x, $y, 'hex');

        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");

        if ($r < 130 || $r > 175) {
            return false;
        }

        if ($g < 170 || $g > 220) {
            return false;
        }

        if ($b > 50) {
            return false;
        }

        return true;
    }
}
