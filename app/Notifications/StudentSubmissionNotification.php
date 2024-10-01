<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Add this
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentSubmissionNotification extends Notification implements ShouldQueue // Add "implements ShouldQueue"
{
    use Queueable;

    protected $student;

    /**
     * Create a new notification instance.
     *
     * @param  $student  The student who submitted the form.
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New Student Form Submission')
                    ->greeting('Hello Admin,')
                    ->line('A new student has submitted their details.')
                    ->line('Name: ' . $this->student->name)
                    ->line('Email: ' . $this->student->email)
                    ->line('CV has been uploaded. Please review the submission in the admin panel.')
                    ->line('Thank you for managing student submissions.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'student_id' => $this->student->id,
            'name' => $this->student->name,
            'email' => $this->student->email,
        ];
    }
}
