<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SetupPasswordNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public string $setupUrl)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Configura tu contraseña - iCita',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.setup-password',
            with: ['setupUrl' => $this->setupUrl],
        );
    }
}
