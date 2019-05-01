<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Notifications\Notifiable;

class PodcastEpisode extends Model implements Feedable
{
    use Notifiable;

    protected $fillable = [
        'title',
        'email',
        'short_description',
        'long_description',
        'episode',
        'avatar_url',
        'reddit_url',
        'website_url',
        'github_url',
        'twitter_url',
        'app_url',
        'is_visible',
        'is_uploaded',
        'is_featured',
        'private_notes',
        'file_size',
        'file_duration',
    ];

    protected $hidden = [
        'email',
        'private_notes',
        'is_visible',
        'is_featured',
    ];

    /*
    protected $dates = [
        'created_at',
        'updated_at',
        'published_at'
    ];
    */

    public function scopeVisible($query)
    {
        $query->where('is_visible', '=', true);
    }

    public function scopeUploaded($query)
    {
        $query->where('is_uploaded', '=', true);
    }

    public static function getFeedItems()
    {
        return cache('flutter-featured-podcast-list');
    }

    public function toFeedItem()
    {
        $summary = htmlentities(strip_tags($this->short_description . "\n" . $this->long_description));

        if ($this->reddit_url) {
            $summary .= "\n\n" . $this->reddit_url;
        }

        return FeedItem::create()
            ->id($this->downloadUrl())
            ->title($this->podcastTitle())
            ->updated(\Carbon\Carbon::parse($this->published_at))
            ->summary($summary)
            ->link(json_encode([$this->url(), $this->downloadUrl(), $this->file_duration]))
            ->author("Hillel Coren");
    }

    public function podcastTitle()
    {
        return $this->episode . '. ' . $this->title;
    }

    public function listDescription()
    {
        return $this->is_uploaded ? $this->short_description : 'Coming soon...';
    }

    public function url()
    {
        return url('podcast/episodes/' . ($this->episode ?: '0') . '/' . str_slug($this->title));
    }

    public function adminUrl()
    {
        return url('podcast/admin/' . $this->id);
    }

    public function downloadUrl($format = 'mp3')
    {
        return url('podcast/download/episode-' . $this->episode . '.' . $format);
    }

    public function mp3Path($format = 'mp3')
    {
        return storage_path('/mp3s/episode-' . $this->episode . '.' . $format);
    }

    public function avatarUrl()
    {
        return url('avatars/avatar-' . $this->id . '.jpg?no_cache=' . $this->updated_at);
    }
}
