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
            'title' => 'required|unique:flutter_apps,title',
            'screenshot1_url' => 'required|url',
            'short_description' => 'required|max:140',
            'long_description' => 'required',
        ]);

        $input = $request->all();
        $user_id = auth()->user()->id;
        $app = $this->appRepo->store($input, $user_id);

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
        $app = $this->appRepo->getBySlug($slug);

        return view('flutter_apps.show', compact('app'));

    }

    /**
     * Show a specified app
     *
     * @param  FlutterApp $slug
     * @return Response
     */
    public function edit($id)
    {
        $app = $this->appRepo->getById($id);

        return view('flutter_apps.edit', compact('app'));

    }
}
