<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DisableTwoFactorAuthentication extends Mailable
{
    use Queueable, SerializesModels;
    public array $Data;

    public function __construct(array $Data)
    {
        $this->Data = $Data;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Disable Two-Factor Authentication',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.disable-two-factor-authentication',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}