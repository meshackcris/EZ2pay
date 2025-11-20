<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositApprovedNotification extends Notification
{
    use Queueable;
    public $transaction;
    public function __construct( $transaction )
    {
        $this->transaction = $transaction;
    }
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Deposit Has Been Manually Approved - ' . config('app.name'))
            ->view('emails.deposit_approved', [
                'user' => $notifiable,
                'transaction' => $this->transaction,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
