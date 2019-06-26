<?php

namespace App\Http\Controllers;

class FlutterArtifactController extends Controller
{
    public function index()
    {
        $data = [
            'useBlackHeader' => true,
        ];

        return view('flutter_artifacts.index', $data);
    }
}
