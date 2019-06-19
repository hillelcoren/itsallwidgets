<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventSubmitted extends Notification
{
    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New event: ' . $this->event->event_name)
                    ->greeting('Hello!')
                    ->line('A new event has been submitted!')
                    ->action('View Event', $this->event->route());
    }
}
