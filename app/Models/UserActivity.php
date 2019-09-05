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

    /**
     * Get all of the owning activity models.
     */
    public function activity()
    {
        return $this->morphTo();
    }

    /**
     * @return array|\Spatie\Feed\FeedItem
     */
    public function toFeedItem()
    {
        // TODO: Implement toFeedItem() method.
    }
}
