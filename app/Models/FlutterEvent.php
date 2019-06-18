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
        'twitter_url',
        'slug',
        'description',
    ];

    protected $hidden = [
        'is_visible',
        'is_approved',
    ];


    public function route()
    {
        return '/flutter-event/' . $this->slug . '/edit';
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
        return 'Join us for $event organized by $handle';
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
