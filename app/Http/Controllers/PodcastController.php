<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PodcastEpisode;
use App\Repositories\PodcastRepository;
use App\Http\Requests\EditPodcastEpisode;
use App\Http\Requests\StorePodcastEpisode;
use App\Http\Requests\UpdatePodcastEpisode;
use App\Notifications\InterviewRequested;

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

        User::admin()->notify(new InterviewRequested($episode));

        return redirect('/podcast')->with(
            'status',
            'Your request has been successfully submitted!'
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

        $data = [
            'episode' => $episode,
            'long_description' => $this->linkify(nl2br(e($episode->long_description)), ['http'], ['target' => '_blank']),
        ];

        return view('podcasts.show', $data);
    }

    public function download($episode, $format)
    {
        $episode = $this->podcastRepo->getByEpisode($episode);

        $episode->download_count++;
        $episode->save();

        $file_path = $episode->mp3Path($format);

        return response()->download($file_path);
    }

    // https://gist.github.com/jasny/2000705
    private function linkify($value, $protocols = array('http', 'mail'), array $attributes = array())
    {
        // Link attributes
        $attr = '';
        foreach ($attributes as $key => $val) {
            $attr = ' ' . $key . '="' . htmlentities($val) . '"';
        }

        $links = array();

        // Extract existing links and tags
        $value = preg_replace_callback('~(<a .*?>.*?</a>|<.*?>)~i', function ($match) use (&$links) { return '<' . array_push($links, $match[1]) . '>'; }, $value);

        // Extract text links for each protocol
        foreach ((array)$protocols as $protocol) {
            switch ($protocol) {
                case 'http':
                case 'https':   $value = preg_replace_callback('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { if ($match[1]) $protocol = $match[1]; $link = $match[2] ?: $match[3]; return '<' . array_push($links, "<a $attr href=\"$protocol://$link\">$link</a>") . '>'; }, $value); break;
                case 'mail':    $value = preg_replace_callback('~([^\s<]+?@[^\s<]+?\.[^\s<]+)(?<![\.,:])~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"mailto:{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
                case 'twitter': $value = preg_replace_callback('~(?<!\w)[@#](\w++)~', function ($match) use (&$links, $attr) { return '<' . array_push($links, "<a $attr href=\"https://twitter.com/" . ($match[0][0] == '@' ? '' : 'search/%23') . $match[1]  . "\">{$match[0]}</a>") . '>'; }, $value); break;
                default:        $value = preg_replace_callback('~' . preg_quote($protocol, '~') . '://([^\s<]+?)(?<![\.,:])~i', function ($match) use ($protocol, &$links, $attr) { return '<' . array_push($links, "<a $attr href=\"$protocol://{$match[1]}\">{$match[1]}</a>") . '>'; }, $value); break;
            }
        }

        // Insert all link
        return preg_replace_callback('/<(\d+)>/', function ($match) use (&$links) { return $links[$match[1] - 1]; }, $value);
    }
}
