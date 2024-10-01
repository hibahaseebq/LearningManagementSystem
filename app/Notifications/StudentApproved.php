<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail']; // Using Mailtrap to send the notification
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Registration Has Been Approved')
                    ->greeting('Hello ' . $this->user->name . '!')
                    ->line('Your registration has been approved. Please click the button below to set your password.')
                    ->action('Set Password', url('/password/reset', $this->token))
                    ->line('This link will expire in 24 hours.');
    }
}
