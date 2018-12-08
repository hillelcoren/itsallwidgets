<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar_url',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'name',
        'email',
        'google_id',
        'remember_token',
    ];

    public function addNew($input)
    {
        $check = static::where('google_id', $input['google_id'])->first();

        if (is_null($check)) {
            return static::create($input);
        }

        return $check;
    }

    public function owns($app)
    {
        if ($this->is_admin) {
            return true;
        }

        return $this->id == $app->user_id;
    }

    public function shouldSendTweet()
    {
        if (! config('services.twitter.consumer_key')) {
            return false;
        }

        if (config('app.env') != 'production') {
            return false;
        }

        return $this->is_admin;
    }

    public static function admin()
    {
        return static::where('is_admin', '=', true)->first();
    }
}
