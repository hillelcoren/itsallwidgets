<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Notifications\Notifiable;

class FlutterApp extends Model implements Feedable
{
    use Notifiable;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'long_description',
        'website_url',
        'repo_url',
        'apple_url',
        'google_url',
        'facebook_url',
        'twitter_url',
        'youtube_url',
        'instagram_url',
        'hash_tags',
        'has_gif',
        'has_screenshot_1',
        'has_screenshot_2',
        'has_screenshot_3',
        'is_mobile',
        'is_web',
        'flutter_web_url',
    ];

    protected $hidden = [
        'is_visible',
        'is_approved',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function scopeVisible($query)
    {
        $query->where('is_visible', '=', true);
    }

    public function scopeApproved($query)
    {
        $query->visible()->where('is_approved', '=', true);
    }

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

    public function screenshotUrl($number = false)
    {
        $suffix = $number ? '-' . $number : '';

        return iawUrl() . '/screenshots/app-' . $this->id . $suffix . '.png?updated_at=' . $this->updated_at->format('Y-m-d%20H:m:s');
    }

    public function gifUrl()
    {
        return url('gifs/app-' . $this->id . '.gif?updated_at=' . $this->updated_at->format('Y-m-d%20H:m:s'));
    }

    public function url()
    {
        return iawUrl() . '/flutter-app/' . $this->slug;
    }

    public function twitterHandle()
    {
        if (! $this->twitter_url) {
            return false;
        }

        $parts = explode('/', $this->twitter_url);
        $part = $parts[count($parts) - 1];
        $part = ltrim($part, '@');

        $parts = explode('?', $part);
        $part = $parts[0];

        return '@' . $part;
    }

    public function activities()
    {
        return $this->morphMany('App\Models\UserActivity', 'activity');
    }

    public function matchesUser($user)
    {
        if (! $this->is_approved || ! $this->is_visible) {
            return false;
        }

        return $user->id == $this->user_id;
    }

    public function activityLinkURL()
    {
        return $this->url();
    }

    public function activityLinkTitle()
    {
        return $this->title;
    }

    public function activityMessage()
    {
        return substr($this->short_description . '. ' . $this->long_description, 0, 300);
    }

    public function toObject()
    {
        $obj = new \stdClass;
        $obj->name = $this->title;
        $obj->type = 'app';
        $obj->description = mb_convert_encoding($this->short_description, 'UTF-8', 'UTF-8');
        $obj->url = $this->url();
        $obj->image_url = $this->screenshotUrl();

        return $obj;
    }
}
