<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Notifications\Notifiable;
use App\Models\Traits\FullTextSearch;

class FlutterArtifact extends Model implements Feedable
{
    use Notifiable;
    use FullTextSearch;

    protected $fillable = [
        'title',
        'slug',
        'url',
        'comment',
        'published_date',
        'contents',
        'type',
        'image_url',
        'source_url',
        'repo_url',
        'gif_url',
        'is_approved',
        'is_visible',
        'meta_description',
        'meta_publisher',
        'meta_author',
        'meta_author_url',
        'meta_author_twitter',
        'meta_publisher_twitter',
    ];

    protected $hidden = [
        'is_visible',
        'is_approved',
        'contents',
    ];

    protected $appends = [
        'pretty_published_date',
        'pretty_type',
        'type_class',
        'type_label',
        'domain',
    ];

    protected $searchable = [
        'contents',
        'title',
        'comment',
        'meta_author',
        'meta_publisher',
        'meta_description',
        'meta_author_twitter',
        'meta_publisher_twitter',
    ];

    public function url()
    {
        return url((isIAW() ? 'flutterx/' : '') . $this->slug);
    }

    public function editUrl()
    {
        return $this->url() . '/edit';
    }

    public function link()
    {
        return link_to($this->url(), $this->event_name, ['target' => '_blank']);
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
        $query->where('user_id', '=', $userId);
    }


    public function getPrettyPublishedDateAttribute()
    {
        $time = strtotime($this->published_date);

        return date('D, F jS Y', $time);
    }

    public function getPrettyTypeAttribute()
    {
        return ucwords($this->type);
    }

    public function getDomainAttribute()
    {
        $parse = parse_url($this->url);

        $host = $parse['host'];
        $host = str_replace(['www.', 'blog.'], '', $host);

        return $host;
    }

    public function getTypeClassAttribute()
    {
        if ($this->type == 'article') {
            return 'link';
        } elseif ($this->type == 'video') {
            return 'primary';
        } elseif ($this->type == 'library') {
            return 'dark';
        }
    }

    public function getTypeLabelAttribute()
    {
        if ($this->type == 'article') {
            return 'READ ARTICLE';
        } elseif ($this->type == 'video') {
            return 'WATCH VIDEO';
        } elseif ($this->type == 'library') {
            return 'VIEW LIBRARY';
        }
    }

    public function hasAuthor()
    {
        return $this->authorUrl() || $this->authorName();
    }

    public function hasPublisher()
    {
        return $this->publisherUrl() || $this->publisherName();
    }

    public function authorUrl()
    {
        if ($this->meta_author_url) {
            return $this->meta_author_url;
        } elseif ($this->meta_author_twitter) {
            return 'https://twitter.com/' . $this->meta_author_twitter;
        } else {
            return false;
        }
    }

    public function authorName()
    {
        if ($this->meta_author) {
            return $this->meta_author;
        } elseif ($this->meta_author_twitter) {
            return '@' . $this->meta_author_twitter;
        } else {
            return false;
        }
    }

    public function publisherUrl()
    {
        if ($this->meta_publisher_twitter) {
            return 'https://twitter.com/' . $this->meta_publisher_twitter;
        } else {
            return false;
        }
    }

    public function publisherName()
    {
        if ($this->meta_publisher) {
            return $this->meta_publisher;
        } elseif ($this->meta_publisher_twitter) {
            return '@' . $this->meta_publisher_twitter;
        } else {
            return false;
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

    public function activities()
    {
        return $this->morphMany('App\Models\UserActivity', 'activity');
    }

    public function matchesUser($user)
    {
        if (! $this->is_approved || ! $this->is_visible) {
            return false;
        }

        if ($this->meta_author && strtolower($this->meta_author) == strtolower($user->name)) {
            return true;
        }

        if ($user->website_url && strpos(strtolower($this->url), strtolower($user->website_url)) !== false) {
            return true;
        }

        if ($this->meta_author_twitter && 'https://twitter.com/' . strtolower(rtrim($this->meta_author_twitter, '/')) == strtolower($user->twitter_url)) {
            return true;
        }

        $urls = [
            $user->medium_url,
            $user->github_url,
            $user->youtube_url,
            $user->website_url,
            $user->linkedin_url,
        ];

        foreach ($urls as $url) {
            if (! $url) {
                continue;
            }

            $url = strtolower($url);

            if (strpos(strtolower(rtrim($this->url, '/')), $url) === 0) {
                return true;
            }

            if ($this->meta_author_url && strpos(strtolower(rtrim($this->meta_author_url, '/')), $url) === 0) {
                return true;
            }
        }

        return false;
    }

    public function activityLinkURL()
    {
        return $this->url;
    }

    public function activityLinkTitle()
    {
        return $this->title;
    }

    public function activityMessage()
    {
        return substr($this->meta_description, 0, 300);
    }

    public function toObject()
    {
        $obj = new \stdClass;
        $obj->name = $this->title;
        $obj->description = $this->meta_description;
        $obj->url = $this->url;

        return $obj;
    }
}
