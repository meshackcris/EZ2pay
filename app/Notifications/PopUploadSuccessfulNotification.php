<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class PopUploadSuccessfulNotification extends Notification
{
    use Queueable;

    protected $transaction;
    /**
     * Create a new notification instance.
     */
    public function __construct( $transaction )
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
{
    $transactionTypeLabel = $this->transaction->TransactionType == 5
    ? 'Manual Wire'
    : ($this->transaction->TransactionType == 6 ? 'Manual EFT' : 'Unknown');

    return (new MailMessage)
        ->subject('Proof of Payment Submitted Sucessfully - ' . config('app.name'))
        ->view('emails.pop_submitted_sucessfully', [
            'user' => $notifiable,
            'transaction' => $this->transaction,
            'formattedDate' => $this->transaction->UpdatedAt->format('F j, Y h:i A'),
            'transactionTypeLabel' => $transactionTypeLabel,
        ]); 
}


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
