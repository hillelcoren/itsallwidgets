<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdateProProfile;

class FlutterProController extends Controller
{
    public function index()
    {
        $data = [
            'useBlackHeader' => true,
        ];

        return view('flutter_pro.index', $data);
    }

    public function search()
    {
        $data = [];
        $search = strtolower(request()->search);
        $sortBy = strtolower(request()->sort_by);

        $users = User::whereIsPro(true)
            ->whereNotNull('last_activity')
            ->with('userActivities');

        if ($search) {
            $users->search($search);
        }

        if ($sortBy == 'sort_newest') {
            $users->orderBy('id', 'desc');
        } else if ($sortBy == 'sort_activity') {
            $users->orderBy('last_activity', 'desc');
        } else if ($sortBy == 'sort_apps') {
            $users->orderBy('count_apps', 'desc');
        } else if ($sortBy == 'sort_artifacts') {
            $users->orderBy('count_artifacts', 'desc');
        } else if ($sortBy == 'sort_events') {
            $users->orderBy('count_events', 'desc');
        } else {
            $users->orderByRaw(\DB::raw("count_apps + count_artifacts + (count_events*2) DESC"));
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
            $str = '<pre>' . json_encode($user->toObject(), JSON_PRETTY_PRINT) . '</pre';

            if (request()->instructions) {
                /*
                $str = 'You can use the <a href="' . $user->jsonUrl() . '" target="_blank">JSON feed</a> to create a custom profile with <a href="https://flutter.dev/web" target="_blank">Flutter Web</a>.<p/>'
                    . 'Once the page is setup <a href="' . iawUrl() . '/auth/google?intended_url=profile/edit" target="_blank">click here</a> to configure the Flutter Web URL.'
                    . $str;
                    */

                $str = 'You can use the JSON feed to create a custom profile with Flutter Web<p/>'
                    . $user->jsonUrl() . '<p/>'
                    . 'To make it shareable you just need to accept the developer handle as a query parameter<p/>'
                    . $str;
            }

            return $str;
        } else {
            return response()->json($user->toObject());
        }
    }

    public function edit()
    {
        $user = auth()->user();

        $data = [
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
        $user->save();

        if ($input = $_FILES['avatar']['tmp_name']) {
            $output = public_path("avatars/{$user->profile_key}.png");
            imagepng(imagecreatefromstring(file_get_contents($input)), $output);
        }

        return redirect('/profile/edit')->with(
            'status',
            'Your profile has been successfully updated!');
    }

}
