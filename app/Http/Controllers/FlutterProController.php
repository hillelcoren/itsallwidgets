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

    public function json($handle)
    {
        $user = User::whereHandle(strtolower($handle))->first();

        if (! $user) {
            return redirect(fpUrl());
        }

        if (request()->pretty) {
            return '<pre>' . json_encode($user->toObject(), JSON_PRETTY_PRINT) . '</pre';
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

    public function show($handle)
    {
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
