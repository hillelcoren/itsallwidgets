<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Socialite;
use Exception;
use Auth;

class GoogleController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGoogle()
    {
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
            Auth::loginUsingId($createdUser->id);

            if ($createdUser->wasRecentlyCreated) {
                return redirect('/submit-app')->with('status', 'Your account has been created!');
            } else {
                return redirect('/')->with('status', 'Your are now signed in!');
            }

        } catch (Exception $e) {

            \Log::error('OAUTH ERROR: ' . $e->getMessage());
            return redirect('/');
        }
    }

}
