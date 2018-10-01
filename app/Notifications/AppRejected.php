<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppRejected extends Notification
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
                    ->greeting('Hi,')
                    ->line('We\'re sorry, we won\'t be able to show your submission on the main listing.')
                    ->line('The site is designed for apps which are submitted to either of the app stores.')
                    ->line('The direct link will continue to work.')
                    ->line($this->app->url());
    }
}
