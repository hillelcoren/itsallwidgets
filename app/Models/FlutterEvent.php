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
        'banner_color',
        'address',
        'city',
        'country',
        'event_url',
        'slug',
        'description',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'is_visible',
        'is_approved',
        'view_count',
        'click_count',
        'twitter_click_count',
        'tweet_count',
    ];

    protected $appends = [
        'pretty_event_date',
        'location',
        'text_description',
    ];


    public function url()
    {
        return url('flutter-event/' . $this->slug . '/edit');
    }

    public function mapUrl()
    {
        return 'https://www.google.com/maps/search/?api=1&query=' . $this->latitude . ',' . $this->longitude;
    }

    public function link()
    {
        return link_to($this->url(), $this->event_name, ['target' => '_blank']);
    }

    public function eventLink()
    {
        return link_to($this->event_url, $this->event_name, ['target' => '_blank']);
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

    public function scopeFuture($query)
    {
        $query->visible()->where('event_date', '>=', date('Y-m-d'));
    }

    public function scopeOwnedBy($query, $userId)
    {
        $query->where('user_id', '=', $userId);
    }

    public function defaultBanner()
    {
        return 'Join us at $event';
        //return 'Join us for $event organized by @handle';
    }

    public function getTextBanner()
    {
        $banner = e($this->banner);
        $banner = str_replace('$event', $this->event_name, $banner);
        $banner = str_replace('$city', $this->city ?: $this->address, $banner);

        return $banner;
    }

    public function getBanner($isFeed = false)
    {
        $banner = e($this->banner);
        $onclick = '';

        if (! $isFeed) {
            $onclick = ' onclick="trackBannerClick(\'' . $this->slug . '\', true)" target="_blank"';
        }

        $banner = preg_replace('/@([a-zA-Z0-9_]+)/',
            '<b><a href="http://twitter.com/$1"' . $onclick . '>@$1</a></b>', $banner);
        $banner = preg_replace('/#([a-zA-Z0-9_]+)/',
            '<b><a href="http://twitter.com/hashtag/$1"' . $onclick . '>#$1</a></b>', $banner);

        if (! $isFeed) {
            $onclick = ' onclick="trackBannerClick(\'' . $this->slug . '\')" target="_blank"';
        }

        $eventUrl = '<b><a href="' . $this->event_url .
            '"'. $onclick . '>' .
            $this->event_name . '</a></b>';
        $banner = str_replace('$event', $eventUrl, $banner);
        $banner = str_replace('$city', $this->city ?: $this->address, $banner);

        return $banner;
    }

    public function prettyDate()
    {
        $time = strtotime($this->event_date);

        return date('D, F jS', $time);

    }

    public function getCity()
    {
        return $this->city ?: $this->address;
    }

    public function getTextDescriptionAttribute()
    {
        $str = $this->description;
        $str = str_replace("<br/>", "\n", $str);
        $str = str_replace("</p> ", "</p>\n\n", $str);

        return strip_tags($str);
    }

    public function getPrettyEventDateAttribute()
    {
        return $this->prettyDate();
    }

    public function getLocationAttribute()
    {
        return $this->city ? ($this->city . ', ' . $this->country) : $this->address;
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
