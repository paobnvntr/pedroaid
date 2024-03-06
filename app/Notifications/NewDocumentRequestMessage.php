<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDocumentRequestMessage extends Notification
{
    use Queueable;

    protected $documentRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($documentRequest)
    {
        $this->documentRequest = $documentRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'documentRequest_id' => $this->documentRequest['documentRequest_id'],
            'name' => $this->documentRequest['name'],
            'transaction_type' => 'Document Request',
        ];
    }
}
