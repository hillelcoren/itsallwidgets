<?php

namespace App\Http\Controllers;

use App\Models\FlutterStream;
use App\Models\Language;
use Illuminate\Http\Request;

class FlutterStreamController extends Controller
{
    public function index()
    {
        if (request()->clear_cache) {
            cache()->forget('flutter-stream-list');
            return redirect('/')->with('status', 'App cache has been cleared!');
        }

        $data = [
            'count' => request()->show_all ? FlutterStream::count() : FlutterStream::visible()->count(),
            'banner' => getBanner(),
            'useBlackHeader' => true,
            'showAll' => request()->show_all,
        ];

        return view('flutter_streams.index', $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $sortBy = strtolower(request()->sort_by);
        $source = strtolower(request()->source);
        $isEnglish = request()->is_english;

        $streams = FlutterStream::where(function($query) use ($source, $search, $isEnglish) {

            if (!request()->show_all) {
                $query->visible();
            }

            if ($source) {
                $query->where('source', '=', $source);
            }

            if ($search) {
                $query->search($search);
            }

            if ($isEnglish) {
                $query->english();
            }
        });

        if ($sortBy == 'sort_views') {
            $streams->orderBy('view_count', 'desc');
        } else if ($sortBy == 'sort_likes') {
            $streams->orderBy('like_count', 'desc');
        } else if ($sortBy == 'sort_comments') {
            $streams->orderBy('comment_count', 'desc');
        } else {
            $streams->orderByRaw(\DB::raw("CASE WHEN starts_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR) THEN starts_at ELSE '3000-01-01' END ASC, starts_at DESC"));
        }

        $streams->limit(12)->offset(((request()->page ?: 1) - 1) * 12);

        foreach ($streams->get() as $stream)
        {
            $data[] = $stream->toObject();
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

    public function hideChannel($tld, $stream = false)
    {
        if (! $stream) {
            $stream = $tld;
        }

        $channel = $stream->channel;
        $channel->is_visible = false;
        $channel->save();

        return redirect(url('/') . '?clear_cache=true');
    }

    public function showChannel($tld, $stream = false)
    {
        if (! $stream) {
            $stream = $tld;
        }

        $channel = $stream->channel;
        $channel->is_visible = true;
        $channel->save();

        return redirect(url('/') . '?clear_cache=true');
    }

    public function englishChannel($tld, $stream = false)
    {
        if (! $stream) {
            $stream = $tld;
        }

        $language = Language::where('locale', '=', request()->locale)
            ->firstOrFail();

        $channel = $stream->channel;
        $channel->language_id = $language->id;
        $channel->save();

        return redirect(url('/') . '?clear_cache=true');
    }

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
