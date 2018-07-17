<?php

namespace App\Http\Controllers;

use App\Models\FlutterApp;
use App\Http\Requests;
use App\Http\Requests\EditFlutterApp;
use App\Http\Requests\StoreFlutterApp;
use App\Http\Requests\UpdateFlutterApp;
use Illuminate\Http\Request;
use App\Repositories\FlutterAppRepository;
use App\Notifications\AppSubmitted;

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
        //return view('flutter_apps.legacy_index', compact('apps'));
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
        $user_id = auth()->user()->id;

        $screenshot = $request->file('screenshot');
        $filename = $request->slug . '.' . $screenshot->extension();
        $screenshot->move(public_path('/screenshots'), $filename);
        $input['screenshot1_url'] = '/screenshots/' . $filename;

        $app = $this->appRepo->store($input, $user_id);
        $app->notify(new AppSubmitted());

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
        $app = request()->flutter_app;
        $input = $request->all();

        if ($screenshot = $request->file('screenshot')) {
            $filename = $app->slug . '.' . $screenshot->extension();
            $screenshot->move(public_path('/screenshots'), $filename);
            $input['screenshot1_url'] = '/screenshots/' . $filename;
        }

        $app = $this->appRepo->update($app, $input);

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
