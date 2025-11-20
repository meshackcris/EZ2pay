<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalReceivedConfirmation extends Notification
{
    use Queueable;
public $withdrawal;
public $url;

public function __construct($url, $withdrawal)
{
    $this->url = $url;
    $this->withdrawal = $withdrawal;
}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Action Required: Confirm Receipt of Your Funds - ' . config('app.name'))
            ->view('emails.withdrawal_received', [
                'user' => $notifiable,
                'withdrawal' => $this->withdrawal,
                'confirmation_url' => $this->url,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
