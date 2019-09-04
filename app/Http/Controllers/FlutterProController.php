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
        //$profiles = User::whereIsPro(true)->search($search)->get();
        $profiles = User::whereIsPro(true)->get();

        foreach ($profiles as $profile)
        {
            //$str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

            $obj = new \stdClass;
            $obj->id = $profile->profile_key;
            $obj->name = $profile->name;
            //$obj->contents = $str;

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
