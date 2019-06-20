<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventApproved extends Notification
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
                    ->greeting('Hi,')
                    ->line('Thanks you for submitting your event!')
                    ->line('It has been approved, your banner should now be visible on the main site.')
                    ->line(url('flutter-events'))
                    ->link('Note: we use the browser\'s IP address to geo-locate, it works most of the time but not always (ie, VPNs).');
    }
}
