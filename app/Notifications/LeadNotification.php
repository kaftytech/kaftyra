<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadNotification extends Notification
{
    use Queueable;

    public $lead;
    /**
     * Create a new notification instance.
     */
    public function __construct($lead)
    {
        $this->lead = $lead;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
   // NewLeadNotification.php
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Lead Added',
            'message' => 'A new lead has been added: '.$this->lead->name,
            'url' => route('leads.show', $this->lead->id),
        ];
    }

}
