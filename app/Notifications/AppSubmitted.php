<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class AppSubmitted extends Notification
{
    public function via($notifiable)
    {
        return [TwitterChannel::class];
    }

    public function toTwitter($app)
    {
        $tweet = 'New #Flutter app 🚀 ' . $app->title . ' 🙌 ';

        if ($handle = $app->twitterHandle()) {
            $tweet .= ' ' . $handle;
        }

        if ($app->google_url) {
            $tweet .= ' #Android';
        }

        if ($app->apple_url) {
            $tweet .= ' #iPhone';
        }

        if ($app->repo_url) {
            $tweet .= ' #OpenSource';
        }

        $tweet .= "\n" . $app->url();

        return new TwitterStatusUpdate($tweet);
    }
}
