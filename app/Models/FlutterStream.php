<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\FullTextSearch;

class FlutterStream extends Model implements Feedable
{
    use Notifiable;
    use FullTextSearch;

    protected $searchable = [
        'name',
        'description',
    ];

    public function url()
    {
        return url((isIAW() ? 'flutter-stream/' : '') . $this->video_id);
    }

    public function link()
    {
        return link_to($this->url(), $this->name, ['target' => '_blank']);
    }

    public function scopeVisible($query)
    {
        $query->whereHas('channel', function($query) {
            return $query->where('is_visible', '=', 1);
        });
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\FlutterChannel', 'channel_id');
    }

    public function getVideoUrl()
    {
        if ($this->source == 'youtube') {
            return 'https://www.youtube.com/watch?v=' . $this->video_id;
        } else {
            return '';
        }
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

    public function activityLinkURL()
    {
        return $this->getVideoUrl();
    }

    public function activityLinkTitle()
    {
        return $this->name;
    }

    public function activityMessage()
    {
        $str = substr($this->description, 0, 300);

        return trim(str_replace(['<p>', '</p>', '<br>', '<br/>'], ' ', $str));
    }

    public function toObject()
    {
        $obj = new \stdClass;
        $obj->name = $this->name;
        $obj->type = 'stream';
        $obj->description = mb_convert_encoding($this->description, 'UTF-8', 'UTF-8');
        $obj->url = $this->url();
        $obj->image_url = $this->thumbnail_url;
        $obj->channel_name = $this->channel_name;
        $obj->channel_id = $this->channel_id;

        return $obj;
    }


}
