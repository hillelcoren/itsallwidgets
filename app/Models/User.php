<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Traits\FullTextSearch;

class User extends Authenticatable
{
    use Notifiable;
    use FullTextSearch;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'email',
        'handle',
        'image_url',
        'google_id',
        'avatar_url',
        'profile_url',
        'website_url',
        'github_url',
        'youtube_url',
        'twitter_url',
        'medium_url',
        'linkedin_url',
        'instagram_url',
        'bio',
        'is_pro',
        'is_pro_iaw',
        'is_pro_fe',
        'is_pro_fs',
        'is_pro_fx',
        'is_subscribed',
        'country_code',
        'is_for_hire',
        'is_mentor',
        'is_trainer',
        'last_activity',
        'rss_feed_url',
        'widget',
        'widget_voice',
        'widget_youtube_url',
        'widget_inheritance',
        'widget_version_added',
        'widget_week',
        'widget_description',
        'widget_code_sample',
        'widget_tip',
        'widget_youtube_comment',
        'widget_youtube_handle',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'name',
        'email',
        'google_id',
        'remember_token',
        'updated_at',
        'match_all_videos',
        'is_mentor',
        'is_trainer',
    ];

    protected $searchable = [
        'name',
        'handle',
        'bio',
        'country_code',
        'website_url',
        'github_url',
        'youtube_url',
        'twitter_url',
        'medium_url',
        'linkedin_url',
        'instagram_url',
    ];

    public function userActivities()
    {
        return $this->hasMany('App\Models\UserActivity')->orderBy('id', 'desc');
    }

    public function channel()
    {
        return $this->belongsTo('App\Models\FlutterChannel', 'channel_id');
    }

    public function addNew($input)
    {
        $check = static::where('google_id', $input['google_id'])->first();

        if (is_null($check)) {
            return static::create($input);
        }

        return $check;
    }

    public function owns($app)
    {
        if ($this->is_admin) {
            return true;
        }

        return $this->id == $app->user_id;
    }

    public function shouldSendTweet()
    {
        if (! config('services.twitter.consumer_key')) {
            return false;
        }

        if (config('app.env') != 'production') {
            return false;
        }

        return $this->is_admin;
    }

    public function scopePro($query) {
        return $query->whereIsPro(true)->whereNotNull('last_activity');
    }

    public static function admin()
    {
        return static::where('is_admin', '=', true)->first();
    }

    public function url()
    {
        return fpUrl() . '/' . $this->handle;
    }

    public function jsonUrl($isPretty = false)
    {
        return $this->url() . '/json' . ($isPretty ? '?pretty=true' : '');
    }

    public function imageUrl()
    {
        return fpUrl() . '/' . $this->image_url;
    }

    public function isActivityTypeActive($type)
    {
        if ($type == 'flutter_app' && $this->is_pro_iaw) {
            return true;
        } else if ($type == 'flutter_event' && $this->is_pro_fe) {
            return true;
        } else if ($type == 'flutter_artifact' && $this->is_pro_fx) {
            return true;
        }

        return false;
    }

    function profileUrl()
    {
    	if ($this->profile_url) {
    		return $this->profile_url;
    	} else if ($this->website_url) {
    		return $this->website_url;
    	} else {
    		return $this->jsonUrl(true) . '&instructions=true';
    	}
    }

    public function toObject()
    {
        $obj = new \stdClass;

        if (request()->counts) {
            $obj->id = $this->profile_key;
            $obj->image_url = $this->image_url;
            $obj->profile_url = $this->profileUrl();
        }

        $obj->name = $this->name;
        $obj->handle = $this->handle;
        $obj->bio = $this->bio ?: '';
        $obj->country_code = $this->country_code;
        $obj->is_for_hire = (bool) $this->is_for_hire;
        $obj->website_url = $this->website_url;
        $obj->github_url = $this->github_url;
        $obj->youtube_url = $this->youtube_url;
        $obj->twitter_url = $this->twitter_url;
        $obj->medium_url = $this->medium_url;
        $obj->linkedin_url = $this->linkedin_url;
        $obj->instagram_url = $this->instagram_url;
        $obj->updated_at = $this->updated_at ? $this->updated_at->toIso8601String() : '';

        if (request()->counts) {
            $counts = [];
            if ($this->count_apps > 0) {
                $counts[] = $this->count_apps . ($this->count_apps == 1 ? ' App' : ' Apps');
            }
            if ($this->count_artifacts > 0) {
                $counts[] = $this->count_artifacts . ($this->count_artifacts == 1 ? ' Resource' : ' Resources');
            }
            if ($this->count_events > 0) {
                $counts[] = $this->count_events . ($this->count_events == 1 ? ' Event' : ' Events');
            }

            $obj->counts = join('  •  ', $counts);
            $obj->activity_count = 0;
            $obj->activity_message = '';
            $obj->activity_link_url = '';
            $obj->activity_link_title = '';
        }

        $obj->apps = [];
        $obj->articles = [];
        $obj->videos = [];
        $obj->libraries = [];
        $obj->events = [];

        $activities = $this->userActivities;

        foreach ($activities as $activity) {

            if (! $this->isActivityTypeActive($activity->activity_type)) {
                continue;
            }

            if (request()->counts && ! $obj->activity_message) {
                $obj->activity_count = $activities->count();
                $obj->activity_message = mb_convert_encoding($activity->activity->activityMessage(), 'UTF-8', 'UTF-8');
                $obj->activity_link_url = $activity->activity->activityLinkURL();
                $obj->activity_link_title = mb_convert_encoding($activity->activity->activityLinkTitle(), 'UTF-8', 'UTF-8');
            }

            $activityObj = $activity->activity->toObject();

            if ($activityObj->type == 'app') {
                $type = 'apps';
            } else if ($activityObj->type == 'article') {
                $type = 'articles';
            } else if ($activityObj->type == 'video') {
                $type = 'videos';
            } else if ($activityObj->type == 'library') {
                $type = 'libraries';
            } else if ($activityObj->type == 'event') {
                $type = 'events';
            }

            unset($activityObj->type);

            $obj->{$type}[] = $activityObj;
        }

        if (request()->pretty) {
            if (! count($obj->apps)) {
                unset($obj->apps);
            }
            if (! count($obj->articles)) {
                unset($obj->articles);
            }
            if (! count($obj->videos)) {
                unset($obj->videos);
            }
            if (! count($obj->libraries)) {
                unset($obj->libraries);
            }
            if (! count($obj->events)) {
                unset($obj->events);
            }
        }

        return $obj;
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
}
