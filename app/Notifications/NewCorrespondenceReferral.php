<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCorrespondenceReferral extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($role)
    {
        $this->role=$role;
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
        return (new MailMessage)
            ->subject('New Correspondence Referral')
            ->line('A New Correspondence Referral has been created for you.')
            ->line('Please review it as soon as possible.')
            ->action('Follow This Link To View Details',$this->role=='موظف' ?url('/employee/correspondence-trackings'):url('/admin/correspondence-trackings'))
            ->line('Thank you!');
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
