<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationSuccess extends Mailable
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
            subject: 'Welcome to the SpyBird',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.registration-success',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}