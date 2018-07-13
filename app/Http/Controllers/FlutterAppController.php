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
        $apps = cache('flutter-app-list');

        return view('flutter_apps.index', compact('apps'));
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
            'url' => 'flutter-apps/submit',
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
    public function edit($slug)
    {
        $app = $this->appRepo->getBySlug($slug);

        if ($app->user_id != auth()->user()->id) {
            return redirect('/');
        }

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
    public function store(Request $request)
    {
        $request->merge([
            'slug' => str_slug(request()->title)
        ]);

        $this->validate($request, [
            'title' => 'required|unique:flutter_apps,title',
            'slug' => 'required|unique:flutter_apps,slug',
            'screenshot1_url' => 'required|url',
            'short_description' => 'required|max:140',
            'long_description' => 'required',
        ]);

        $input = $request->all();
        $user_id = auth()->user()->id;
        $app = $this->appRepo->store($input, $user_id);

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
    public function update(Request $request, $slug)
    {
        $app = $this->appRepo->getBySlug($slug);

        if ($app->user_id != auth()->user()->id) {
            return redirect('/');
        }

        $this->validate($request, [
            'title' => 'required|unique:flutter_apps,title,' . $app->id . ',id',
            'screenshot1_url' => 'required|url',
            'short_description' => 'required|max:140',
            'long_description' => 'required',
        ]);

        $app = $this->appRepo->update($app, $request->all());

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
}
