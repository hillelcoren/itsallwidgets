<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InterviewRequested extends Notification
{
    public function __construct($episode)
    {
        $this->episode = $episode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New interview request: ' . $this->episode->title)
                    ->greeting('Hello!')
                    ->line('A new interview has been requested!')
                    ->action('View request', $this->episode->adminUrl());
    }
}
