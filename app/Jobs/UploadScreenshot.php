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
        $this->uploadImage('screenshot');
        $this->uploadImage('screenshot_1', 1);
        $this->uploadImage('screenshot_2', 2);
        $this->uploadImage('screenshot_3', 3);
        $this->uploadImage('screenshot_desktop', 0, true);

        // check for gif
        if ($gif = request()->file('gif')) {
            $filename = 'app-' . $this->app->id . '.gif';
            $gif->move(public_path('/gifs'), $filename);

            $this->app->update([
                'has_gif' => true,
            ]);
        }
    }

    private function uploadImage($name, $number = 0, $isDesktop = false)
    {
        if ($png = request()->file($name)) {
            $suffix = $number ? '-' . $number : '';
            if ($isDesktop) {
                $suffix = '-desktop';
            }

            $filename = 'app-' . $this->app->id . $suffix . '.png';
            $png->move(public_path('/screenshots'), $filename);

            if ($number) {
                $this->app->update([
                    'has_screenshot_' . $number => true,
                ]);
            }

            // check for an error border
            if (!$isDesktop) {
                $image = Image::make(public_path('/screenshots') . '/' . $filename);

                if ($this->isErrorPixel($image, 0, 500)
                    && ! $this->isErrorPixel($image, 50, 500)
                ) {
                    session()->flash('warning', 'We\'ve detected a yellow border around the image, see this GitHub comment for a fix: https://github.com/flutter/flutter/issues/16810#issuecomment-416962229');
                }
            }
        }
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
