<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBrandAddedNotification extends Notification
{
    use Queueable;

    public function __construct(public $brand) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Brand Added - ' . config('app.name'))
            ->view('emails.admin_new_brand_added', [
                'admin' => $notifiable,
                'brand' => $this->brand,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
