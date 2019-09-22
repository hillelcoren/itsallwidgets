<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Socialite;
use Exception;
use Auth;
use App\Console\Commands\ConvertToPro;

class GoogleController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
        if (auth()->check()) {
            return redirect(request()->intended_url ?: url());
        }

        session(['intended_url' => request()->intended_url]);
        session()->save();

        return Socialite::driver('google')->redirect();
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $create['name'] = $user->getName();
            $create['email'] = $user->getEmail();
            $create['google_id'] = $user->getId();
            $create['avatar_url'] = $user->getAvatar();

            $userModel = new User;
            $createdUser = $userModel->addNew($create);
            Auth::loginUsingId($createdUser->id, true);

            dispatch(new ConvertToPro($createdUser));

            $route = session()->pull('intended_url', 'submit');

            return redirect($route)->with('status', 'Your are now signed in!');

        } catch (Exception $e) {

            \Log::error('OAUTH ERROR: ' . $e->getMessage());
            return redirect('/');
        }
    }

}
