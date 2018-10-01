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
use Illuminate\Http\Request;
use App\Repositories\FlutterAppRepository;
use App\Notifications\AppSubmitted;
use App\Notifications\AppApproved;
use App\Notifications\AppRejected;
use App\Jobs\UploadScreenshot;

class FlutterAppController extends Controller
{

    /**
     * @var app\Repositories\FlutterAppRepository;
     */
    protected $appRepo;

    /**
     * GitHub client
     *
     * @var GrahamCampbell\GitHub\Facades\GitHub
     */
    protected $github;

    public function __construct(FlutterAppRepository $appRepo)
    {
        $this->appRepo = $appRepo;
    }

    /**
     * Display list of apps
     *
     * @return Response
     */
    public function index()
    {
        if (request()->clear_cache) {
            cache()->forget('flutter-app-list');
            return redirect('/');
        }

        if (request()->legacy || Crawler::isCrawler()) {
            $view = 'flutter_apps.legacy_index';
        } else {
            $view = 'flutter_apps.index';
        }

        $apps = cache('flutter-app-list') ?: FlutterApp::approved()->latest()->get();

        return view($view, compact('apps'));
    }

    /**
     * Display form to submit app
     *
     * @return Response
     */
    public function create()
    {
        $app = new FlutterApp;

        $data = [
            'app' => $app,
            'url' => 'submit',
            'method' => 'POST',
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

        $data = [
            'app' => $app,
            'url' => 'flutter-app/' . $app->slug,
            'method' => 'PUT',
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
        $app = $this->appRepo->store($input, $user->id);

        dispatch(new UploadScreenshot($app));

        User::admin()->notify(new AppSubmitted($app));

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
        $app = $this->appRepo->update($app, $input);

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
        $app = $this->appRepo->getBySlug($slug);

        return view('flutter_apps.show', compact('app'));
    }

    public function approve(ApproveFlutterApp $request)
    {
        $app = $request->flutter_app;

        if ($app->is_approved) {
            return redirect('/');
        }

        $app->is_approved = true;
        $app->save();

        if (auth()->user()->shouldSendTweet()) {
            $app->notify(new AppApproved());
        }

        return redirect('/')->with('status', 'App has been approved!');
    }

    public function reject(RejectFlutterApp $request)
    {
        $app = $request->flutter_app;

        $app->user->notify(new AppRejected($app));

        return redirect('/')->with('status', 'App has been rejected!');
    }

    public function feature(FeatureFlutterApp $request)
    {
        $app = $request->flutter_app;
        $user = auth()->user();
        $featured = $user->getFeaturedNumber();

        FlutterApp::whereFeatured($featured)->update(['featured' => 0]);

        $app->featured = $featured;
        $app->save();

        if ($app->featured > 0) {
            return redirect('/')->with('status', $app->title. ' is now featured!');
        } else {
            return redirect('/');
        }

    }

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

        $str .= '</urlset>';

        return response($str)->header('Content-Type', 'application/xml');
    }
}
