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
        $url = (string) $episode->avatar_url;

        // Security: only https URLs on public hosts, and only real images. Anything else (a local
        // path such as ../.env, a private IP) is refused, never written to public/avatars.
        if (! preg_match('/^https:\/\//i', $url)) {
            return;
        }

        $host = parse_url($url, PHP_URL_HOST);
        $ip = $host ? gethostbyname($host) : null;

        if (! $ip || ! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return;
        }

        $context = stream_context_create(['http' => ['timeout' => 10, 'follow_location' => 0, 'user_agent' => 'ItsAllWidgetsBot/1.0']]);
        $contents = @file_get_contents($url, false, $context, 0, 5 * 1024 * 1024);

        if (! $contents || ! @getimagesizefromstring($contents)) {
            return;
        }

        $file = public_path('/avatars/avatar-' . $episode->id . '.jpg');

        file_put_contents($file, $contents);
    }

}
