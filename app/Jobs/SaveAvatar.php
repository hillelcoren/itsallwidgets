<?php

namespace App\Jobs;

use Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveAvatar implements ShouldQueue
{
    use Dispatchable, SerializesModels;

    protected $episode;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($episode)
    {
        $this->episode = $episode;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $episode = $this->episode;

        if ($contents = file_get_contents($episode->avatar_url)) {

            $file = public_path('/avatars/avatar-' . $episode->id . '.jpg');

            file_put_contents($file, $contents);
        }
    }

}
