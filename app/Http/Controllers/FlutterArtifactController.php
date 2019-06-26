<?php

namespace App\Http\Controllers;

use App\Models\FlutterArtifact;

class FlutterArtifactController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->is_admin) {
            $artifacts = FlutterArtifact::get();
        } else {
            $artifacts = FlutterArtifact::approved()->get();
        }

        $data = [
            'useBlackHeader' => true,
            'artifacts' => $artifacts,
        ];

        return view('flutter_artifacts.index', $data);
    }
}
