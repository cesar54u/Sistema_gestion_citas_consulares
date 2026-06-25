<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerificacionCuentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $verificationUrl;

    public function __construct(public User $user)
    {
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✉️ Verifica tu Correo - Consulado Honorario España | Maracay',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.verificacion-cuenta',
            with: [
                'user'            => $this->user,
                'verificationUrl' => $this->verificationUrl,
            ],
        );
    }
}
