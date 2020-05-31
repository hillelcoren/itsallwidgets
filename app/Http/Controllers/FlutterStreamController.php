<?php

namespace App\Http\Controllers;

use App\Models\FlutterStream;
use Illuminate\Http\Request;

class FlutterStreamController extends Controller
{
    public function index($tld = '')
    {
        if ($tld == 'com') {
            return redirect('https://flutterstreams.com');
        }

        if (request()->clear_cache) {
            cache()->forget('flutter-stream-list');
            return redirect('/')->with('status', 'App cache has been cleared!');
        }

        if (auth()->check() && auth()->user()->is_admin) {
            $streams = FlutterStream::latest()->get();
        } else {
            $streams = cache('flutter-stream-list');
        }

        $data = [
            'streams' => $streams,
            'banner' => getBanner(),
            'useBlackHeader' => true,
        ];

        return view('flutter_streams.index', $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $streams = FlutterStream::approved()->search($search)->get();

        foreach ($streams as $stream)
        {
            $index = strpos(strtolower($stream->description), $search);
            $str = substr($stream->contents, $index, 800);
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

            $obj = new \stdClass;
            $obj->id = $stream->id;
            $obj->contents = $str;

            $data[] = $obj;
        }

        return response()->json($data);
    }

    public function update()
    {
        \Artisan::call('itsallwidgets:load_streams');

        return 'done';
    }

    public function show($tld, $stream = false)
    {
        if (! $stream) {
            $stream = $tld;
        }

        $data = [
            'stream' => $stream,
        ];

        if (!isGL()) {
            $data['useBlackHeader'] = true;
        }

        return view('flutter_streams.show', $data);
    }

    /*
    public function hide($tld, $stream = false)
    {
        if (! $stream) {
            $stream = $tld;
        }

        $stream->is_visible = false;
        $stream->save();

        return redirect(url('/') . '?clear_cache=true');
    }
    */

    public function jsonFeed(Request $request)
    {
        $streams = cache('flutter-stream-list');
        $data = [];

        foreach ($streams as $stream) {
            $data[] = $stream->toObject();
        }

        return response()->json($data)->withCallback($request->input('callback'));;
    }

    public function sitemap()
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $str .= '<url><loc>' . config('app.url') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1</priority></url>';

        $streams = cache('flutter-stream-list');

        foreach ($streams as $stream) {
            $str .= '<url>'
            . '<loc>' . $stream->url() . '</loc>'
            . '<lastmod>' . $stream->updated_at->format('Y-m-d') . '</lastmod>'
            . '<changefreq>weekly</changefreq>'
            . '<priority>0.5</priority>'
            . '</url>';
        }

        $str .= '</urlset>';

        return response($str)->header('Content-Type', 'application/xml');
    }
}
