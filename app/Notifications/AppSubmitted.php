<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\Twitter\TwitterChannel;
use NotificationChannels\Twitter\TwitterStatusUpdate;

class AppSubmitted extends Notification
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New app: ' . $this->app->title)
                    ->greeting('Hello!')
                    ->line('A new app has been submitted!')
                    ->action('View App', $this->app->url());
    }
}
