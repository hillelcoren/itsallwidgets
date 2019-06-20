<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Notifications\Notifiable;

class FlutterEvent extends Model implements Feedable
{
    use Notifiable;

    protected $fillable = [
        'event_name',
        'event_date',
        'banner',
        'address',
        'event_url',
        'slug',
        'description',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'is_visible',
        'is_approved',
    ];


    public function url()
    {
        return url('flutter-event/' . $this->slug . '/edit');
    }

    public function mapUrl()
    {
        return 'https://www.google.com/maps/search/?api=1&query=' . $this->latitude . ',' . $this->longitude;
    }

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

    public function scopeOwnedBy($query, $userId)
    {
        $query->visible()->where('user_id', '=', $userId);
    }

    public function defaultBanner()
    {
        return 'Join us for $event organized by @handle';
    }

    public function getBanner()
    {
        $banner = e($this->banner);

        $banner = preg_replace('/@([a-zA-Z0-9_]+)/',
            '<b><a href="http://twitter.com/$1" target="_blank" onclick="trackBannerClick(\'' . $this->slug . '\', true)">@$1</a></b>', $banner);
        $banner = preg_replace('/#([a-zA-Z0-9_]+)/',
            '<b><a href="http://twitter.com/hashtag/$1" target="_blank" onclick="trackBannerClick(\'' . $this->slug . '\', true)">#$1</a></b>', $banner);

        $eventUrl = '<b><a href="' . $this->event_url .
            '" target="_blank" onclick="trackBannerClick(\'' . $this->slug . '\')">' .
            $this->event_name . '</a></b>';
        $banner = str_replace('$event', $eventUrl, $banner);

        return $banner;
    }

    public function prettyDate()
    {
        $time = strtotime($this->event_date);

        return date('l jS, F Y', $time);

    }

    public function toFeedItem()
    {
        /*
        return FeedItem::create()
            ->id($this->slug)
            ->title($this->title)
            ->summary($this->short_description)
            ->updated($this->updated_at)
            ->link('/flutter-app/' . $this->slug)
            ->author($this->title);
        */
    }
}
