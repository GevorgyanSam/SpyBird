<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountTermination extends Mailable
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
            subject: 'Account Termination',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.account-termination',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}