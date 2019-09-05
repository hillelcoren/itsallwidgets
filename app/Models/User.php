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
        'is_pro_fx',
        'is_subscribed',
        'country_code',
        'is_for_hire',
        'last_activity',
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

    public static function admin()
    {
        return static::where('is_admin', '=', true)->first();
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

    public function toObject()
    {
        $obj = new \stdClass;
        $obj->id = $this->profile_key;
        $obj->image_url = $this->image_url;
        $obj->name = $this->name;
        $obj->handle = $this->handle;
        $obj->bio = $this->bio;
        $obj->country_code = $this->country_code;
        $obj->is_for_hire = $this->is_for_hire;
        $obj->website_url = $this->website_url;
        $obj->github_url = $this->github_url;
        $obj->youtube_url = $this->youtube_url;
        $obj->twitter_url = $this->twitter_url;
        $obj->medium_url = $this->medium_url;
        $obj->linkedin_url = $this->linkedin_url;
        $obj->instagram_url = $this->instagram_url;

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

        $obj->counts = join(' • ', $counts);
        $obj->activity_count = 0;
        $obj->activity_message = '';
        $obj->activity_link_url = '';
        $obj->activity_link_title = '';
        $obj->activities = [];

        $activities = $this->userActivities;

        foreach ($activities as $activity) {

            if (! $this->isActivityTypeActive($activity->activity_type)) {
                continue;
            }

            if (! $obj->activity_message) {
                $obj->activity_count = $activities->count();
                $obj->activity_message = mb_convert_encoding($activity->activity->activityMessage(), 'UTF-8', 'UTF-8');
                $obj->activity_link_url = $activity->activity->activityLinkURL();
                $obj->activity_link_title = mb_convert_encoding($activity->activity->activityLinkTitle(), 'UTF-8', 'UTF-8');
            }

            $obj->activities[] = $activity->activity->toObject();
        }

        return $obj;
    }
}
