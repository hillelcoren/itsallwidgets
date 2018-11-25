<?php

namespace App\Http\Controllers;

use App\Models\PodcastEpisode;
use App\Repositories\PodcastRepository;
use App\Http\Requests\StorePodcastEpisode;
use App\Http\Requests\UpdatePodcastEpisode;

class PodcastController extends Controller
{
    public function __construct(PodcastRepository $podcastRepo)
    {
        $this->podcastRepo = $podcastRepo;
    }

    public function index()
    {
        return view('podcasts.index');
    }

    public function create()
    {
        $episode = new PodcastEpisode;

        $data = [
            'episode' => $episode,
            'url' => 'podcast',
            'method' => 'POST',
        ];

        return view('podcasts.edit', $data);

    }

    public function store(StorePodcastEpisode $request)
    {
        $input = $request->all();
        $episode = $this->podcastRepo->store($input);

        return redirect('/flutter-podcast/' . $episode->episode)->with(
            'status',
            'Your podcast episode has been successfully added!'
        );
    }

    public function show($episode)
    {
        $episode = $this->podcastRepo->getByEpisde($episode);

        return view('podcasts.show', compact('episode'));
    }

}
