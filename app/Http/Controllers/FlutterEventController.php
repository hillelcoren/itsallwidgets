<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FlutterEvent;
use App\Http\Requests;
use App\Http\Requests\EditFlutterEvent;
use App\Http\Requests\StoreFlutterEvent;
use App\Http\Requests\UpdateFlutterEvent;
use App\Http\Requests\ApproveFlutterEvent;
use App\Http\Requests\FeatureFlutterEvent;
use App\Http\Requests\RejectFlutterEvent;
use Illuminate\Http\Request;
use App\Repositories\FlutterEventRepository;
use App\Notifications\EventSubmitted;
use App\Notifications\EventApproved;
use App\Notifications\EventRejected;

class FlutterEventController extends Controller
{
    /**
     * @var app\Repositories\FlutterEventRepository;
     */
    protected $eventRepo;

    public function __construct(FlutterEventRepository $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }


    public function index()
    {
        $user = auth()->user();

        if ($user->is_admin) {
            $events = FlutterEvent::latest()->get();
        } else {
            $events = FlutterEvent::latest()->ownedBy($user->id)->get();
        }

        if ($events->isEmpty()) {
            return redirect('flutter-event/submit');
        }

        return view('flutter_events.index', compact('events'));
    }

    public function create()
    {
        $event = new FlutterEvent;

        $data = [
            'event' => $event,
            'url' => 'flutter-event',
            'method' => 'POST',
        ];

        return view('flutter_events.edit', $data);
    }

    /**
     * Show a specified app
     *
     * @param  FlutterEvent $slug
     * @return Response
     */
    public function edit(EditFlutterEvent $request)
    {
        $event = request()->flutter_event;

        $data = [
            'event' => $event,
            'url' => 'flutter-event/' . $event->slug,
            'method' => 'PUT',
        ];

        return view('flutter_events.edit', $data);
    }

    /**
     * Store app to the database
     *
     * @param  Request $request
     * @return Response
     */
    public function store(StoreFlutterEvent $request)
    {
        $input = $request->all();
        $user = auth()->user();
        $event = $this->eventRepo->store($input, $user->id);

        User::admin()->notify(new EventSubmitted($event));

        return redirect('flutter-events')->with(
            'status',
            'Your event has been successfully added!'
        );
    }

    /**
     * Store app to the database
     *
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateFlutterEvent $request, $slug)
    {
        $event = $request->flutter_event;
        $input = $request->all();
        $event = $this->eventRepo->update($event, $input);

        return redirect('/flutter-event/' . $event->slug . '/edit')->with(
            'status',
            'Your event has been successfully updated!'
        );
    }

    /**
     * Show a specified app
     *
     * @param  FlutterEvent $slug
     * @return Response
     */
    public function show($slug)
    {
        $event = $this->eventRepo->getBySlug($slug);

        return view('flutter_events.show', compact('event'));
    }

    public function approve(ApproveFlutterEvent $request)
    {
        $event = $request->flutter_event;

        if ($event->is_approved) {
            return redirect('/');
        }

        $event->is_approved = true;
        $event->save();

        if (auth()->user()->shouldSendTweet()) {
            $event->notify(new EventApproved());
        }

        return redirect('/flutter-events')->with('status', 'Event has been approved!');
    }

    public function reject(RejectFlutterEvent $request)
    {
        $event = $request->flutter_event;

        $event->user->notify(new EventRejected($event));

        return redirect('/flutter-events')->with('status', 'Event has been rejected!');
    }

    public function trackClicked(FlutterEvent $event, $clickType)
    {
        if ($clickType == 'twitter') {
            $event->twitter_click_count++;
        } else {
            $event->click_count++;
        }

        $event->save();

        return 'SUCCESS';
    }

    /*
    public function feature(FeatureFlutterEvent $request)
    {
        $app = $request->flutter_app;

        FlutterEvent::where('featured', '>', 0)->decrement('featured');

        $app->featured = 16;
        $app->save();

        $app->user->notify(new AppFeatured($app));

        return redirect('/flutter-events')->with('status', $app->title. ' is now featured!');
    }

    /*
    public function sitemap()
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $str .= '<url><loc>' . config('app.url') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1</priority></url>';

        $apps = cache('flutter-app-list');

        foreach ($apps as $app) {
            $str .= '<url>'
            . '<loc>' . $app->url() . '</loc>'
            . '<lastmod>' . $app->updated_at->format('Y-m-d') . '</lastmod>'
            . '<changefreq>weekly</changefreq>'
            . '<priority>0.5</priority>'
            . '</url>';
        }

        $episodes = cache('flutter-podcast-list');

        foreach ($episodes as $episode) {
            if (! $episode->episode) {
                continue;
            }

            $str .= '<url>'
            . '<loc>' . $episode->url() . '</loc>'
            . '<lastmod>' . $episode->updated_at->format('Y-m-d') . '</lastmod>'
            . '<changefreq>weekly</changefreq>'
            . '<priority>0.5</priority>'
            . '</url>';
        }

        $str .= '</urlset>';

        return response($str)->header('Content-Type', 'application/xml');
    }
    */
}
