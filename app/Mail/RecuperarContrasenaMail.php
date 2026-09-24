<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecuperarContrasenaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $resetUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔑 Recuperar Contraseña - Consulado Honorario España | Maracay',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.recuperar-contrasena',
            with: [
                'user'     => $this->user,
                'resetUrl' => $this->resetUrl,
            ],
        );
    }
}
