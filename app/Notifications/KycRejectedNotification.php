<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class KycRejectedNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your KYC Has Been Rejected - ' . config('app.name'))
            ->view('emails.kyc_rejected', [
                'user' => $notifiable,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
