<?php

namespace App\Http\Controllers;

use App\Models\FlutterArtifact;

class FlutterArtifactController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $artifacts = FlutterArtifact::latest()->get();
        } else {
            $artifacts = FlutterArtifact::latest()->approved()->get();
        }

        $data = [
            'useBlackHeader' => true,
            'artifacts' => $artifacts,
        ];

        return view('flutter_artifacts.index', $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $artifacts = FlutterArtifact::approved()->search($search)->get();

        foreach ($artifacts as $artifact)
        {
            $index = strpos(strtolower($artifact->contents), $search);
            $str = substr($artifact->contents, $index, 800);
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

            $obj = new \stdClass;
            $obj->id = $artifact->id;
            $obj->contents = $str;

            $data[] = $obj;
        }

        return response()->json($data);
    }

    public function update()
    {
        \Artisan::call('itsallwidgets:load_artifacts');

        return 'done';
    }

    public function show()
    {
        return '';
    }
}
