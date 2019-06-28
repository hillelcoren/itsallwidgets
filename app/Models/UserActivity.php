<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Feed\Feedable;

class UserActivity extends Model implements Feedable
{
    use Notifiable;

    protected $fillable = [
    ];

    protected $hidden = [
        'is_visible',
    ];

    protected $appends = [
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function flutterApps()
    {
        return $this->morphMany('App\FlutterApp', 'activity');
    }

    public function flutterEvents()
    {
        return $this->morphMany('App\FlutterEvent', 'activity');
    }

    public function flutterArtifacts()
    {
        return $this->morphMany('App\FlutterArtifact', 'activity');
    }

    /**
     * @return array|\Spatie\Feed\FeedItem
     */
    public function toFeedItem()
    {
        // TODO: Implement toFeedItem() method.
    }
}
