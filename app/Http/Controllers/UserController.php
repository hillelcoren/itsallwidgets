<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    public function edit()
    {
        $data = [
            'user' => auth()->user(),
        ];

        return view('user.edit', $data);
    }

    public function update()
    {
        return view('user.edit');
    }
}
