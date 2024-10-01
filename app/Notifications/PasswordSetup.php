<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordSetup extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Set Up Your Password')
                    ->greeting('Hello!')
                    ->line('You have been added to the system.')
                    ->action('Set Password', url('/password/reset?token=' . $this->token . '&email=' . $notifiable->email))
                    ->line('This link will expire in 24 hours.')
                    ->line('If you did not request this, no further action is required.');
    }
}
