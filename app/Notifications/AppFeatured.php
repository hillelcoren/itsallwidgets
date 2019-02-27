<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppFeatured extends Notification
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
                    ->subject('Your app has been featured!')
                    ->greeting('Hi,')
                    ->line('Thank you for submitting your app!')
                    ->line('We just wanted to let you know that it\'s been featured by one of the site\'s editors.')
                    ->action('View Site', config('app.url'));
    }
}
