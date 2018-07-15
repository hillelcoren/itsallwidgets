<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

class FlutterApp extends Model implements Feedable
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
        'id',
        'is_visible',
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

    public static function getFeedItems()
    {
        return cache('flutter-app-list');
    }

    public function toFeedItem()
    {
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary($this->short_description)
            ->updated($this->updated_at)
            ->link('/flutter-app/' . $this->slug)
            ->author($this->title);
    }
}
