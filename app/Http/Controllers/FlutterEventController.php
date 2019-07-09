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
        if (request()->clear_cache) {
            cache()->forget('flutter-event-list');
            return redirect('/')->with('status', 'App cache has been cleared!');
        }

        if (auth()->check() && auth()->user()->is_admin) {
            $events = FlutterEvent::orderBy('event_date', 'asc')->future()->get();
        } else {
            $events = FlutterEvent::orderBy('event_date', 'asc')->approved()->future()->get();
        }

        $locationKey = \Request::getClientIp() . '_latitude';
        $data = [
            'events' => $events,
            'useBlackHeader' => true,
            'hasLocation' => cache()->has($locationKey),
        ];

        return view('flutter_events.index', $data);
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

        return redirect($event->editUrl())->with(
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

        return redirect($event->editUrl())->with(
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
    public function show($tld, $event = false)
    {
        if (! $event) {
            $event = $tld;
        }

        $data = [
            'event' => $event,
            'useBlackHeader' => true,
        ];

        return view('flutter_events.show', $data);
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

    public function groups()
    {
        $events = FlutterEvent::orderBy('event_date')->approved()->get();

        $str = '';
        $groups = [];

        foreach ($events as $event) {
            if (isset($groups[$event->meetup_group_id])) {
                continue;
            }

            $groups[$event->meetup_group_id] = true;
            $str .= $event->event_date . ' • ' . $event->meetup_group_name . ': ' . $event->event_name . '<br/>';
        }

        return $str;
    }

    public function jsonFeed(Request $request)
    {
        $events = FlutterEvent::orderBy('event_date', 'desc')->approved()->get();
        $data = [];

        foreach ($events as $event) {
            $obj = new \stdClass;
            $obj->event_name = $event->event_name;
            $obj->event_url = $event->event_url;
            $obj->event_date = $event->event_date;
            $obj->image_url = url($event->image_url);
            $obj->address = $event->address;
            $obj->city = $event->getCity();
            $obj->latitude = floatval($event->latitude);
            $obj->longitude = floatval($event->longitude);
            $obj->text_message = $event->getTextBanner();
            $obj->html_message = $event->getBanner(true);
            $obj->description = $event->description;
            $data[] = $obj;
        }

        return response()->json($data)->withCallback($request->input('callback'));;
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
    */

    public function sitemap()
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $str .= '<url><loc>' . config('app.url') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1</priority></url>';

        $events = cache('flutter-event-list');

        foreach ($events as $event) {
            $str .= '<url>'
            . '<loc>' . $event->url() . '</loc>'
            . '<lastmod>' . $event->updated_at->format('Y-m-d') . '</lastmod>'
            . '<changefreq>weekly</changefreq>'
            . '<priority>0.5</priority>'
            . '</url>';
        }

        $str .= '</urlset>';

        return response($str)->header('Content-Type', 'application/xml');
    }
}
