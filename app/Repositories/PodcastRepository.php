<?php

namespace app\Repositories;

use App\Models\PodcastEpisode;

class PodcastRepository
{
    public function getByEpisde($episode)
    {
        return PodcastEpisode::where('episode', $episode)->firstOrFail();
    }

    public function store($input)
    {
        $app = new PodcastEpisode;
        $app->fill($input);
        $app->save();

        return $app;
    }
}
