<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;
use NotificationChannels\FacebookPoster\FacebookPosterChannel;
use NotificationChannels\FacebookPoster\FacebookPosterPost;

class AppApproved extends Notification
{
    public function via($app)
    {
        return [
            TwitterChannel::class,
            //FacebookPosterChannel::class,
        ];
    }

    public function toTwitter($app)
    {
        $tweet = 'New #Flutter App! 🚀 ' . $app->title . ' 🙌 ';

        if ($handle = $app->twitterHandle()) {
            $tweet .= ' ' . $handle;
        }

        if ($app->google_url) {
            $tweet .= ' #Android';
        }

        if ($app->apple_url) {
            $tweet .= ' #iPhone';
        }

        if ($app->is_web) {
            $tweet .= ' #FlutterWeb';
        }
        
        if ($app->is_template) {
            $tweet .= ' #Template';
        } else if ($app->repo_url) {
            $tweet .= ' #OpenSource';
        }

        $tweet .= "\n" . $app->url();

        return new TwitterStatusUpdate($tweet);
    }

    public function toFacebookPoster($app)
    {
        return new FacebookPosterPost('');
    }
}
