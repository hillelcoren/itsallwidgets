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
    ];

    protected $hidden = [

    ];
}
