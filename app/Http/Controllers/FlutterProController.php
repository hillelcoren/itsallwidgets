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
            ->with('userActivities')
            ->limit(12);

        if ($search) {
            $users->search($search);
        }

        if ($sortBy == 'newest') {
            $users->orderBy('id', 'desc');
        } else {
            
        }

        foreach ($users->get() as $user)
        {
            //$str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
            $obj = new \stdClass;
            $obj->id = $user->profile_key;
            $obj->name = $user->name;
            $obj->handle = $user->handle;
            $obj->bio = $user->bio;
            $obj->country_code = $user->country_code;
            $obj->is_for_hire = $user->is_for_hire;
            $obj->website_url = $user->website_url;
            $obj->github_url = $user->github_url;
            $obj->youtube_url = $user->youtube_url;
            $obj->twitter_url = $user->twitter_url;
            $obj->medium_url = $user->medium_url;
            $obj->linkedin_url = $user->linkedin_url;
            $obj->instagram_url = $user->instagram_url;

            $activities = $user->userActivities;

            if ($activities->count() == 0) {
                continue;
            }

            $activity = $activities[0];

            $obj->activity_count = $activities->count();
            $obj->activity_message = $activity->activity->activityMessage();
            $obj->activity_link_url = $activity->activity->activityLinkURL();
            $obj->activity_link_title = $activity->activity->activityLinkTitle();

            $data[] = $obj;
        }

        return response()->json($data);
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
