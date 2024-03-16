<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentRequestMail extends Mailable implements ShouldQueue
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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->customSubject)
                    ->markdown('email.documentRequestMail');
    }
}
