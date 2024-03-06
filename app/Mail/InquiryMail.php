<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InquiryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $mailData;
    public $customSubject;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData, $customSubject = null)
    {
        $this->mailData = $mailData;
        $this->customSubject = $customSubject;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->customSubject ?? 'Inquiry Received';
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.inquiryMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
