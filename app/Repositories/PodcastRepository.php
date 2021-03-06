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
        $title = ucwords(str_replace('-', ' ', $title));

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
        $episode = new PodcastEpisode;
        $episode->fill($input);
        $episode->save();

        return $episode;
    }

    public function update($episode, $input)
    {
        $episode->fill($input);

        if ($episode->is_uploaded && ! $episode->published_at) {
            $episode->published_at = \Carbon\Carbon::now();
        }

        $episode->save();

        return $episode;
    }
}
