<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewLogin extends Mailable
{
    use Queueable, SerializesModels;
    public object $Data;

    public function __construct(object $Data)
    {
        $this->Data = $Data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Login Detected On Your Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-login',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}