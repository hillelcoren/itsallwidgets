<?php

namespace app\Repositories;

use App\Models\PodcastEpisode;

class PodcastRepository
{
    public function getByEpisode($episode)
    {
        return PodcastEpisode::where('episode', $episode)->first();
    }

    public function getByTitle($title)
    {
        return PodcastEpisode::where('title', $title)->first();
    }

    public function store($input)
    {
        $app = new PodcastEpisode;
        $app->fill($input);
        $app->save();

        return $app;
    }
}
