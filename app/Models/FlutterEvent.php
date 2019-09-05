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
        'is_visible',
        'rsvp_limit',
        'rsvp_yes',
        'rsvp_waitlist',
        'directions',
        'meetup_id',
        'meetup_group_id',
        'meetup_group_name',
        'meetup_group_url',
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
        'distance',
        'text_description',
    ];


    public function url()
    {
        return url((isIAW() ? 'flutter-event/' : '') . $this->slug);
    }

    public function editUrl()
    {
        return $this->url() . '/edit';
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

    public function getDistanceAttribute()
    {
        $ip = \Request::getClientIp();
        $banner = false;

        if (! cache()->has($ip . '_latitude')) {
            return 0;
        }

        $lat1 = cache($ip . '_latitude');
        $lon1 = cache($ip . '_longitude');

        if (!$lat1 || !$lon1) {
            return 0;
        }

        $lat2 = $this->latitude;
        $lon2 = $this->longitude;

        // https://stackoverflow.com/a/30556851/497368
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        return $miles;
    }

    public function getTextDescriptionAttribute()
    {
        $str = $this->description;
        $str = str_replace("&amp;", "and", $str);
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

    public function activities()
    {
        return $this->morphMany('App\Models\UserActivity', 'activity');
    }

    public function matchesUser($user)
    {
        return strpos(strtolower($this->description), strtolower($user->name)) !== false;
    }

    public function activityLinkURL()
    {
        return $this->event_url;
    }

    public function activityLinkTitle()
    {
        return $this->event_name;
    }

    public function activityMessage()
    {
        if ($this->event_date < date('Y-m-d')) {
            return 'Will be speaking at :link';
        } else {
            return 'Gave a talk at :link';
        }
    }
}
