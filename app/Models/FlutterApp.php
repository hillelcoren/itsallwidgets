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

        return url('screenshots/app-' . $this->id . $suffix . '.png?updated_at=' . $this->updated_at->format('Y-m-d%20H:m:s'));
    }

    public function gifUrl()
    {
        return url('gifs/app-' . $this->id . '.gif?updated_at=' . $this->updated_at->format('Y-m-d%20H:m:s'));
    }

    public function url()
    {
        return url('flutter-app/' . $this->slug);
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

    /**
     * Get all of the owning activity models.
     */
    public function activity()
    {
        return $this->morphTo();
    }
}
