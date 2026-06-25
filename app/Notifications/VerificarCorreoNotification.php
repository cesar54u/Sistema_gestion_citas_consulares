<?php

namespace App\Notifications;

use App\Mail\VerificacionCuentaMail;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

class VerificarCorreoNotification extends BaseVerifyEmail
{
    /**
     * Overrides the default Laravel verification email
     * to use our custom branded Mailable.
     */
    public function toMail(mixed $notifiable): \Illuminate\Mail\Mailable
    {
        return (new VerificacionCuentaMail($notifiable))
            ->to($notifiable->correo_electronico);
    }
}
