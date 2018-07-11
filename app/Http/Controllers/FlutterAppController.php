<?php

namespace App\Http\Controllers;

use App\Models\FlutterApp;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\FlutterAppRepository;

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
        $apps = FlutterApp::latest()->get();

        return view('flutter_apps.index', compact('apps'));
    }

    /**
     * Display form to submit app
     *
     * @return Response
     */
    public function create()
    {
        return view('flutter_apps.create');
    }

    /**
     * Store app to the database
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:apps,title',
            'screenshot1_url' => 'required|url',
            'short' => 'required|max:140',
            'description' => 'required'
        ]);

        $app = [
            'title' => $request->input('title'),
            'slug' => str_slug($request->input('title')),
            'website_url' => $request->input('website_url'),
            'repo_url' => $request->input('repo_url'),
            'apple_url' => $request->input('apple_url'),
            'google_url' => $request->input('google_url'),
            'twitter_url' => $request->input('twitter_url'),
            'facebook_url' => $request->input('facebook_url'),
            'screenshot1_url' => $request->input('screenshot1_url'),
            'short' => $request->input('short'),
            'description' => $request->input('description')
        ];

        $this->appRepo->store($app);

        return redirect('/flutter-app/' . $app['slug'])->with(
            'status',
            "Your submission has been successfully added!"
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
        $app = $this->appRepo->show($slug);

        /*
        $repoArray = explode('/', $app->repo_url);

        $username = $repoArray[3];
        $repo = $repoArray[4];

        $repoStats = $this->github->repo()->show($username, $repo);

        return view('apps.show', compact('app', 'repoStats', 'username', 'repo'));
        */

        return view('flutter_apps.show', compact('app'));

    }
}
