<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountTerminationConfirmation extends Mailable
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
            subject: 'Account Termination Confirmation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.account-termination-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}