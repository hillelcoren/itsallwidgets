<?php

namespace App\Http\Controllers;

use Crawler;
use App\Models\User;
use App\Models\FlutterApp;
use App\Http\Requests;
use App\Http\Requests\EditFlutterApp;
use App\Http\Requests\StoreFlutterApp;
use App\Http\Requests\UpdateFlutterApp;
use App\Http\Requests\ApproveFlutterApp;
use App\Http\Requests\FeatureFlutterApp;
use App\Http\Requests\RejectFlutterApp;
use App\Http\Requests\HideFlutterApp;
use App\Http\Requests\DeleteFlutterApp;
use Illuminate\Http\Request;
use App\Repositories\FlutterAppRepository;
use App\Repositories\FlutterEventRepository;
use App\Notifications\AppSubmitted;
use App\Notifications\AppApproved;
use App\Notifications\AppRejected;
use App\Notifications\AppFeatured;
use App\Jobs\UploadScreenshot;
use Abraham\TwitterOAuth\TwitterOAuth;

class FlutterAppController extends Controller
{

    /**
     * @var app\Repositories\FlutterAppRepository;
     */
    protected $appRepo;

    /**
     * @var app\Repositories\FlutterEventRepository;
     */
    protected $eventRepo;

    /**
     * GitHub client
     *
     * @var GrahamCampbell\GitHub\Facades\GitHub
     */
    protected $github;

    public function __construct(FlutterAppRepository $appRepo, FlutterEventRepository $eventRepo)
    {
        $this->appRepo = $appRepo;
        $this->eventRepo = $eventRepo;
    }

    /**
     * Display list of apps
     *
     * @return Response
     */
    public function index()
    {
        //\Auth::loginUsingId(1, true);

        if (request()->clear_cache) {
            cache()->forget('flutter-app-list');
            return redirect('/')->with('status', 'App cache has been cleared!');
        }
            
        if (request()->legacy || Crawler::isCrawler()) {
            $view = 'flutter_apps.legacy_index';
            $apps = FlutterApp::approved()->orderBy('featured', 'desc')->orderBy('store_review_count', 'desc')->limit(40)->get();
        } else {
            $view = 'flutter_apps.index';
            $apps = "[]";
        }

        $data = [
            'app_count' => FlutterApp::approved()
                            ->whereIsTemplate(false)
                            ->count(),
            'banner' => getBanner(),
            'apps' => $apps,
        ];

        return view($view, $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $sortBy = strtolower(request()->sort_by);
        $platform = strtolower(request()->platform);

        $apps = FlutterApp::approved();

        if ($search) {
            $apps->search($search);
        }

        if (request()->filter_open_source) {
            $apps->where('repo_url', '!=', '');
        }

        if (request()->filter_template) {
            $apps->where('is_template', '=', true);
        } else {
            $apps->where('is_template', '=', false);
        }

        if (request()->filter_platform == 'platform_mobile') {
            $apps->where('is_mobile', '=', true);
        } else if (request()->filter_platform == 'platform_desktop') {
            $apps->where('is_desktop', '=', true);
        } else if (request()->filter_platform == 'platform_web') {
            $apps->where('is_web', '=', true);
        } else if (request()->filter_platform == 'platform_campaign') {
            $apps->where('is_mobile', '=', true)
                ->where('campaign_id', '=', 1)
                ->where('campaign_is_first_app', '!=', 0);
        }

        if (request()->sort_by == 'sort_oldest') {
            $apps->orderBy('id', 'asc');
        } else if (request()->sort_by == 'sort_newest') {
            $apps->orderBy('id', 'desc');
        } else if (request()->sort_by == 'sort_installs') {
            $apps->orderBy('store_download_count', 'desc');
        } else if (request()->sort_by == 'sort_rating') {
            $apps->orderBy('store_rating', 'desc')->orderBy('store_review_count', 'desc');
        } else  {
            $apps->orderBy('featured', 'desc')->orderBy('store_review_count', 'desc');
        } 

        $apps->limit(20)->offset(((request()->page ?: 1) - 1) * 20);

        foreach ($apps->get() as $app)
        {
            $data[] = $app->toArray();
        }

        return response()->json($data);
    }


    /**
     * Display form to submit app
     *
     * @return Response
     */
    public function create($campaign = '')
    {
        $gradients = file_get_contents(base_path('public/gradients.json'));
        $gradients = json_decode($gradients);

        $gradientOptions = [];
        foreach ($gradients as $gradient) {
            $gradientOptions[join($gradient->colors, ', ')] = $gradient->name;
        }

        asort($gradientOptions);

        $gradientOptions = [
            '#7468E6, #C44B85' => 'Default', 
            '' => 'Custom',
        ] + $gradientOptions;

        $app = new FlutterApp;
        $app->is_mobile = true;
        $app->background_colors = '#7468E6, #C44B85';
        $app->background_rotation = 135;
        $app->font_color = '#FFFFFF';

        $data = [
            'app' => $app,
            'url' => 'submit',
            'method' => 'POST',
            'campaign' => $campaign,
            'gradients' => $gradientOptions,
            'selectedGradient' => $app->background_colors,
        ];

        return view('flutter_apps.edit', $data);
    }

    /**
     * Show a specified app
     *
     * @param  FlutterApp $slug
     * @return Response
     */
    public function edit(EditFlutterApp $request)
    {
        $app = request()->flutter_app;

        $gradients = file_get_contents(base_path('public/gradients.json'));
        $gradients = json_decode($gradients);

        $gradientOptions = [];
        foreach ($gradients as $gradient) {
            $gradientOptions[join($gradient->colors, ', ')] = $gradient->name;
        }

        asort($gradientOptions);

        $gradientOptions = [
            '#7468E6, #C44B85' => 'Default', 
            '' => 'Custom',
        ] + $gradientOptions;
        
        $data = [
            'app' => $app,
            'url' => 'flutter-app/' . $app->slug,
            'method' => 'PUT',
            'campaign' => false,
            'gradients' => $gradientOptions,
            'selectedGradient' => array_key_exists($app->background_colors, $gradientOptions) ? $app->background_colors : '',
        ];

        return view('flutter_apps.edit', $data);
    }

    /**
     * Store app to the database
     *
     * @param  Request $request
     * @return Response
     */
    public function store(StoreFlutterApp $request)
    {
        $input = $request->all();
        $user = auth()->user();

        if (!$input['background_colors']) {
            $input['background_colors'] = $input['custom_color1'] . ', ' . $input['custom_color2'];
        }

        $app = $this->appRepo->store($input, $user->id);

        dispatch(new UploadScreenshot($app));

        if (strpos($app->website_url, '.inoru.') !== false) {
            // do nothing
        } else if (strpos($app->website_url, '.appdupe.') !== false) {
            // do nothing
        } else {
            User::admin()->notify(new AppSubmitted($app));
        }

        return redirect('/flutter-app/' . $app['slug'])->with(
            'status',
            'Your application has been successfully added!'
        );
    }

    /**
     * Store app to the database
     *
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateFlutterApp $request, $slug)
    {
        $app = $request->flutter_app;
        $input = $request->all();

        if (!$input['background_colors']) {
            $input['background_colors'] = $input['custom_color1'] . ', ' . $input['custom_color2'];
        }

        $app = $this->appRepo->update($app, $input);

        if ($request->flutterpro) {
            $user = auth()->user();
            $user->is_pro_iaw = true;
            $user->save();
        }

        dispatch(new UploadScreenshot($app, 'screenshot'));

        return redirect('/flutter-app/' . $app->slug)->with(
            'status',
            'Your application has been successfully updated!'
        );
    }

    /**
     * Show a specified app
     *
     * @param  FlutterApp $slug
     * @return Response
     */
    public function show($slug)
    {
        $url = request()->url();
        $matchUrl = isHomestead() ? 'http://dev.itsallwidgets.com/' : 'https://itsallwidgets.com/';

        if (! str_starts_with($url, $matchUrl)) {
            return redirect('https://itsallwidgets.com/' . $slug);
        }

        $app = $this->appRepo->getBySlug($slug);

        if (! $app) {
            abort(403, 'Not found');
        }

        return view('flutter_apps.show', compact('app'));
    }

    public function approve(ApproveFlutterApp $request)
    {
        $app = $request->flutter_app;

        if ($app->is_approved) {
            return redirect('/');
        }

        if (request()->is_template) {
            $app->is_template = true;
        }

        $app->is_approved = true;
        $app->save();

        if (request()->feature) {
            FlutterApp::where('featured', '>', 0)->decrement('featured');

            $app->featured = 32;
            $app->save();

            $app->user->notify(new AppFeatured($app));
        }

        if (! $app->is_template && auth()->user()->shouldSendTweet()) {
            //$app->notify(new AppApproved());

            $tweet = 'New #Flutter App! 🚀 ' . $app->title . ' 🙌 ';

            if ($handle = $app->twitterHandle()) {
                $tweet .= ' ' . $handle;
            }
    
            if ($app->microsoft_url) {
                $tweet .= ' #Windows';
            }
    
            if ($app->apple_url && $app->is_desktop) {
                $tweet .= ' #macOS';
            }
    
            if ($app->snapcraft_url) {
                $tweet .= ' #Linux';
            }
    
            if ($app->google_url) {
                $tweet .= ' #Android';
            }
    
            if ($app->apple_url && $app->is_mobile) {
                $tweet .= ' #iPhone';
            }
    
            if ($app->is_web) {
                $tweet .= ' #FlutterWeb';
            }
    
            if ($app->repo_url) {
                $tweet .= ' #OpenSource';
            }
    
            if ($app->is_template) {
                $tweet .= ' #Template';
            }
    
            $tweet .= "\n" . $app->url();
            
            $twitter = new TwitterOAuth(
                config('services.twitter.consumer_key'),
                config('services.twitter.consumer_secret'),
                config('services.twitter.access_token'),
                config('services.twitter.access_secret')
            );
            
            $parameters = [
                'text' => $tweet,
            ];

            $twitter->setApiVersion('2');
            $twitter->post('tweets', $parameters, true);
        }

        return redirect('/' . $app->slug)->with('status', 'App has been approved!');
    }

    public function reject(RejectFlutterApp $request)
    {
        $app = $request->flutter_app;

        $app->user->notify(new AppRejected($app));

        return redirect('/')->with('status', 'App has been rejected!');
    }

    public function hide(HideFlutterApp $request)
    {
        $app = $request->flutter_app;

        $app->is_visible = false;
        $app->save();

        return redirect('/')->with('status', 'App has been hidden!');
    }

    public function delete(DeleteFlutterApp $request)
    {
        $app = $request->flutter_app;
        $app->delete();
        $app->forceDelete();

        $user = auth()->user();
        $user->count_apps--;
        $user->save();

        return redirect('/')->with('status', 'App has been deleted!');
    }

    public function feature(FeatureFlutterApp $request)
    {
        $app = $request->flutter_app;

        FlutterApp::where('featured', '>', 0)->decrement('featured');

        $app->featured = 32;
        $app->save();

        $app->user->notify(new AppFeatured($app));

        return redirect('/' . $app->slug)->with('status', $app->title. ' is now featured!');
    }

    public function hideWeb(HideFlutterApp $request)
    {
        $app = $request->flutter_app;

        $app->is_web = false;
        $app->flutter_web_url = null;
        $app->save();

        return redirect('/')->with('status', 'App has been hidden from web!');
    }

    public function featureWeb(FeatureFlutterApp $request)
    {
        return redirect('/');

        /*
        $app = $request->flutter_app;

        FlutterApp::where('featured', '>', 0)->decrement('featured');

        $app->featured = 32;
        $app->save();

        return redirect('/' . $app->slug)->with('status', $app->title. ' is now featured!');
        */
    }

    public function jsonFeed(Request $request)
    {
        $apps = FlutterApp::approved()->orderBy('created_at', 'desc');

        if ($request->open_source) {
            $apps = $apps->where('repo_url', '!=', '');
        }

        $apps = $apps->get();
        $data = [];

        foreach ($apps as $app) {
            $data[] = $app->toObject();
        }

        return response()->json($data)->withCallback($request->input('callback'));;
    }

    public function sitemap()
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $str .= '<url><loc>' . config('app.url') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1</priority></url>';

        $apps = FlutterApp::approved()->orderBy('created_at', 'desc')->get();

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

    public function badge()
    {
        $count = FlutterApp::where('user_id', '=', auth()->user()->id)
            ->where('campaign_id', '=', 1)
            ->count();

        if ($count == 0 && request()->secret != 'dash') {
            return redirect('/');
        }

        return view('flutter_apps.badge');
    }
}
