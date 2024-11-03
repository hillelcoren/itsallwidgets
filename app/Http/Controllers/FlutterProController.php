<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Language;
use App\Models\FlutterChannel;
use App\Http\Requests\UpdateProProfile;
use Illuminate\Http\Request;

class FlutterProController extends Controller
{
    public function index()
    {
        $countryInfo = \DB::table('users')
                 ->select('country_code', \DB::raw('count(*) as total'))
                 ->where('is_pro', '=', 1)
                 ->where('country_code', '!=', '')
                 ->whereNotNull('last_activity')
                 ->groupBy('country_code')
                 ->get();

        $countries = [];

        foreach ($countryInfo->all() as $country) {
            $countries[$country->country_code] = \Locale::getDisplayRegion('-' . $country->country_code);
        }

        asort($countries);

        $data = [
            'useBlackHeader' => true,
            'count' => User::pro()->count(),
            'banner' => getBanner(),
            'countries' => $countries,
        ];

        return view('flutter_pro.index', $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $sortBy = strtolower(request()->sort_by);
        $platform = strtolower(request()->platform);

        $users = User::pro()->with('userActivities');

        if ($search) {
            $users->search($search);
        }

        if (request()->has('for_hire')) {
            $users->where('is_for_hire', '=', 1);
        }

        if (request()->has('portfolio')) {
            $users->where(function($query){
                $query->where('profile_url', '!=', '')
                    ->orWhere('website_url', '!=', '');
            });
        }

        if (request()->country_code) {
            $users->where('country_code', '=', request()->country_code);
        }

        if ($platform == 'github') {
            $users->where('github_url', '!=', '');
        } else if ($platform == 'youtube') {
            $users->where('youtube_url', '!=', '');
        } else if ($platform == 'twitter') {
            $users->where('twitter_url', '!=', '');
        } else if ($platform == 'medium') {
            $users->where('medium_url', '!=', '');
        } else if ($platform == 'linkedin') {
            $users->where('linkedin_url', '!=', '');
        } else if ($platform == 'instagram') {
            $users->where('instagram_url', '!=', '');
        }

        if ($sortBy == 'sort_newest') {
            $users->orderBy('id', 'desc');
        } else if ($sortBy == 'sort_activity') {
            $users->orderBy('last_activity', 'desc')->orderBy('id', 'desc');
        } else if ($sortBy == 'sort_apps') {
            $users->orderBy('count_apps', 'desc');
        } else if ($sortBy == 'sort_artifacts') {
            $users->orderBy('count_artifacts', 'desc');
        } else if ($sortBy == 'sort_events') {
            $users->orderBy('count_events', 'desc');
        } else {
            //$users->orderByRaw(\DB::raw("count_apps + count_artifacts + (count_events*2) DESC"));
            $users->orderByRaw(\DB::raw("count_apps + count_artifacts DESC"));
        }

        $users->limit(12)->offset(((request()->page ?: 1) - 1) * 12);

        foreach ($users->get() as $user)
        {
            $data[] = $user->toObject();
        }

        return response()->json($data);
    }

    public function json($tld, $handle = false)
    {
        if (! $handle) {
            $handle = $tld;
        }

        $user = User::whereHandle(strtolower($handle))->first();

        if (! $user) {
            return redirect(fpUrl());
        }

        if (request()->pretty) {
            $str = '<pre>' . e(json_encode($user->toObject(), JSON_PRETTY_PRINT)) . '</pre';

            return $str;
        } else {
            return response()->json($user->toObject());
        }
    }


    public function jsonFeed(Request $request)
    {
        $users = User::pro()->get();
        $data = [];

        foreach ($users as $user) {
            $data[] = $user->toObject();
        }

        return response()->json($data)->withCallback($request->input('callback'));;
    }


    public function edit()
    {
        $user = auth()->user();

        $widgets = [
            'SafeArea',
            'Expanded',
            'Wrap',
            'AnimatedContainer',
            'Opacity',
            'FutureBuilder',
            'FadeTransition',
            'FloatingActionButton',
            'PageView',
            'Table',
            'SliverAppBar',
            'SliverList',
            'FadeInImage',
            'StreamBuilder',
            'InheritedModel',
            'ClipRRect',
            'Hero',
            'CustomPaint',
            'Tooltip',
            'FittedBox',
            'LayoutBuilder',
            'AbsorbPointer',
            'Transform',
            'ImageFilter',
            'Align',
            'Positioned',
            'AnimatedBuilder',
            'Dismissible',
            'SizedBox',
            'ValueListenableBuilder',
            'Draggable',
            'AnimatedList',
            'Flexible',
            'MediaQuery',
            'Spacer',
            'InheritedWidget',
            /*
            'AnimatedIcon',
            'AspectRatio',
            'LimitedBox',
            'Placeholder',
            'RichText',
            'ReorderableListView',
            'AnimatedSwitcher',
            'AnimatedPositioned',
            'AnimatedPadding',
            'IndexedStack',
            'Semantics',
            'ConstrainedBox',
            'Stack',
            'AnimatedOpacity',
            'FractionallySizedBox',
            'ListView',
            'ListTile',
            'Container',
            'SelectableText',
            'DataTable',
            'Slider',
            'AlertDialog',
            'AnimatedCrossFade',
            'DraggableScrollableSheet',
            */
        ];
        $usedWigets = User::whereNotNull('widget')
            ->pluck('widget')
            ->all();
        $availableWidgets = array_diff($widgets, $usedWigets);

        $actors = [
            'Andrew Brogdon',
            'Andrew Fitz Gibbon',
            'Emily Fortuna',
            'Filip Hráček',
        ];

        $libraries = [
            'Animation',
            'Cupertino',
            'Foundation',
            'Gestures',
            'Material',
            'Painting',
            'Physics',
            'Rendering',
            'Scheduler',
            'Semantics',
            'Services',
            'Widgets',
        ];

        $data = [
            'languages' => Language::orderBy('name')->get(),
            'widgets' => $widgets,
            'libraries' => $libraries,
            'availableWidgets' => $availableWidgets,
            'actors' => $actors,
            'user' => $user,
            'useBlackHeader' => true,
        ];

        return view('user.edit', $data);
    }

    public function show($tld, $handle = false)
    {
        if (! $handle) {
            $handle = $tld;
        }

        $user = User::whereHandle(strtolower($handle))->first();

        if (! $user) {
            return redirect(fpUrl());
        }

        $data = [
            'user' => $user,
            'useBlackHeader' => true,
        ];

        return view('user.show', $data);
    }

    public function update(UpdateProProfile $request)
    {
        $user = auth()->user();
        $user->fill($request->all());
        $user->website_url = rtrim($user->website_url, '/');
        $user->github_url = rtrim($user->github_url, '/');
        $user->youtube_url = rtrim($user->youtube_url, '/');
        $user->twitter_url = rtrim($user->twitter_url, '/');
        $user->medium_url = rtrim($user->medium_url, '/');
        $user->linkedin_url = rtrim($user->linkedin_url, '/');
        $user->instagram_url = rtrim($user->instagram_url, '/');
        $user->save();

        if ($input = $_FILES['avatar']['tmp_name']) {
            $output = public_path("avatars/{$user->profile_key}.png");
            imagepng(imagecreatefromstring(file_get_contents($input)), $output);
            $this->resize_png($output, $output, 300, 300);
        }

        if (request()->youtube_channel_id) {
            $channel = FlutterChannel::where('channel_id', '=', request()->youtube_channel_id)
                        ->where('source', '=', 'youtube')
                        ->first();

            if (! $channel) {
                $channel = new FlutterChannel;
                $channel->source = 'youtube';
                $channel->channel_id = request()->youtube_channel_id;
                $channel->name = '';
                $channel->description = '';
                $channel->custom_url = '';
                $channel->thumbnail_url = '';
                $channel->country = '';
            }

            $channel->is_visible = true;
            $channel->language_id = request()->language_id;
            $channel->match_all_videos = request()->match_videos == 'all';
            $channel->save();
            $channel->fresh();

            $user->channel_id = $channel->id;
            $user->save();
        } else if ($user->channel_id) {
            $user->channel_id = null;
            $user->save();
        }

        return redirect('/profile/edit')
                ->with('status','Your profile has been successfully updated!');
    }

    // https://www.php.net/manual/en/function.imagepng.php#60128
    function resize_png($src,$dst,$dstw,$dsth) {
        list($width, $height, $type, $attr) = getimagesize($src);
        $im = imagecreatefrompng($src);
        $tim = imagecreatetruecolor($dstw,$dsth);
        imagecopyresampled($tim,$im,0,0,0,0,$dstw,$dsth,$width,$height);
        $tim = $this->toPalette2($tim,false,255);
        imagepng($tim,$dst);
    }

    function toPalette2($image, $dither, $ncolors) {
        $width = imagesx( $image );
        $height = imagesy( $image );
        $colors_handle = ImageCreateTrueColor( $width, $height );
        ImageCopyMerge( $colors_handle, $image, 0, 0, 0, 0, $width, $height, 100 );
        ImageTrueColorToPalette( $image, $dither, $ncolors );
        ImageColorMatch( $colors_handle, $image );
        ImageDestroy($colors_handle);
        return $image;
    }

    public function sitemap()
    {
        $str = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
        $str .= '<url><loc>' . config('app.url') . '</loc><lastmod>' . date('Y-m-d') . '</lastmod><changefreq>daily</changefreq><priority>1</priority></url>';

        $users = User::whereIsPro(true)
            ->whereNotNull('last_activity')
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $str .= '<url>'
            . '<loc>' . $user->url() . '</loc>'
            . '<lastmod>' . $user->updated_at->format('Y-m-d') . '</lastmod>'
            . '<changefreq>weekly</changefreq>'
            . '<priority>0.5</priority>'
            . '</url>';
        }

        $str .= '</urlset>';

        return response($str)->header('Content-Type', 'application/xml');
    }

    public function cards()
    {
        if (!auth()->check() || !auth()->user()->widget) {
            return redirect('/');
        }

        $users = User::where('widget', '!=' , '')->get();

        return view('flutter_pro.cards', ['users' => $users]);
    }
}
