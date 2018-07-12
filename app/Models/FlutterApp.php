<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlutterApp extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'long_description',
        'screenshot1_url',
        'website_url',
        'repo_url',
        'apple_url',
        'google_url',
        'facebook_url',
        'twitter_url',
        'youtube_url',
    ];

    protected $hidden = [

    ];

    public function setAppleUrlAttribute($value)
    {
        if ($value && ! $this->apple_released) {
            $this->attributes['apple_released'] = now();
        }

        $this->attributes['apple_url'] = $value;
    }

    public function setGoogleUrlAttribute($value)
    {
        if ($value && ! $this->google_released) {
            $this->attributes['google_released'] = now();
        }

        $this->attributes['google_url'] = $value;
    }

}
