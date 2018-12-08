<?php

namespace App\Http\Controllers;

use Log;
use Session;

class HomeController extends Controller
{
    public function about()
    {
        return view('about');
    }

    public function terms()
    {
        return view('terms');
    }

    public function logError()
    {
        $count = Session::get('error_count', 0);
        Session::put('error_count', ++$count);
        if ($count > 20) {
            return 'logged';
        }

        $context = [
            'user_id' => auth()->check() ? auth()->user()->id : 0,
            'url' => request()->url,
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        ];

        Log::error(request()->error, $context);
    }
}
