<?php

namespace App\Http\Controllers;

use App\Models\PodcastEpisode;
use App\Repositories\PodcastRepository;
use App\Http\Requests\EditPodcastEpisode;
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
        if (request()->clear_cache) {
            cache()->forget('flutter-podcast-list');

            return redirect('/podcast')->with('status', 'Podcast cache has been cleared!');
        }

        if (auth()->user() && auth()->user()->is_admin) {
            $episodes = PodcastEpisode::orderBy('episode', 'desc')->orderBy('created_at', 'desc')->get();
        } else {
            $episodes = cache('flutter-podcast-list');
        }

        $data = [
            'episodes' => $episodes,
        ];

        return view('podcasts.index', $data);
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

        return redirect('/podcast/' . $episode->episode)->with(
            'status',
            'Your podcast episode has been successfully added!'
        );
    }

    public function edit(EditPodcastEpisode $request, $episode)
    {
        $data = [
            'episode' => $episode,
            'url' => $episode->adminUrl(),
            'method' => 'PUT',
        ];

        return view('podcasts.edit', $data);
    }

    public function update(UpdatePodcastEpisode $request, $episode)
    {
        $input = $request->all();
        $episode = $this->podcastRepo->update($episode, $input);

        /*
        if ($mp3 = request()->file('mp3')) {
            $filename = 'episode-' . $episode->id . '.mp3';
            $mp3->move(storage_path('/mp3s'), $filename);

            $episode->update([
                'is_uploaded' => true,
            ]);
        }
        */
        
        return redirect($episode->adminUrl())->with(
            'status',
            'Your podcast episode has been successfully updated!'
        );
    }

    public function show($episode, $title = '')
    {
        $episode = $this->podcastRepo->getByEpisodeOrTitle($episode, $title);

        if (! $episode) {
            return redirect('/podcast');
        }

        return view('podcasts.show', compact('episode'));
    }

}
