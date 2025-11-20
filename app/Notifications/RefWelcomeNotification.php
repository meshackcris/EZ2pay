<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefWelcomeNotification extends Notification
{
    use Queueable;

    public function __construct(public $user) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to - ' . config('app.name'))
            ->view('emails.ref_welcome', [
                'admin' => $notifiable,
                'user' => $this->user,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
