<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class StreamsImported extends Notification
{
    public function __construct()
    {
        
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Streams imported')
                    ->greeting('Hello!')
                    ->line('The Flutter live streams have finished importing')
                    ->action('View Site', 'https://flutterstreams.com');
    }
}
