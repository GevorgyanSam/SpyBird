<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnableTwoFactorAuthentication extends Mailable
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
            subject: 'Enable Two-Factor Authentication',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.enable-two-factor-authentication',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}