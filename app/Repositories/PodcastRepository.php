<?php

namespace app\Repositories;

use App\Models\PodcastEpisode;

class PodcastRepository
{
    public function getById($id)
    {
        return PodcastEpisode::find($id);
    }

    public function getByEpisode($episode)
    {
        return PodcastEpisode::where('episode', $episode)->first();
    }

    public function getByTitle($title)
    {
        return PodcastEpisode::where('title', $title)->first();
    }

    public function getByEpisodeOrTitle($episode, $title)
    {
        $episode = $this->getByEpisode($episode);

        if (! $episode) {
            $episode = $this->getByTitle($title);
        }

        return $episode;
    }

    public function store($input)
    {
        $app = new PodcastEpisode;
        $app->fill($input);
        $app->save();

        return $app;
    }

    public function update($app, $input)
    {
        $app->fill($input);
        $app->save();

        return $app;
    }
}
