<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnableTwoFactorAuthenticationConfirmation extends Mailable
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
            subject: 'Two-Factor Authentication Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.enable-two-factor-authentication-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}