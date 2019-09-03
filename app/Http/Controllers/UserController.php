<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function edit()
    {
        $user = auth()->user();

        if (!$user->is_pro) {
            return redirect('/');
        }

        $data = [
            'user' => $user,
            'useBlackHeader' => true,
        ];

        return view('user.edit', $data);
    }

    public function update()
    {
        return view('user.edit');
    }

    public function joinPro()
    {
        $user = auth()->user();

        if ($user->is_pro) {
            return redirect('/profile');
        }

        $handle = str_slug($user->name, '');
        $counter = 1;

        if (User::whereHandle($handle)->count()) {
            while (User::whereHandle($handle . $counter)->count()) {
                $counter++;
            }

            $handle = $handle . $counter;
        }

        $user->is_pro = true;
        $user->handle = $handle;
        $user->save();

        return redirect('/profile');
    }
}
