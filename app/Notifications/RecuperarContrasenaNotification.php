<?php

namespace App\Notifications;

use App\Mail\RecuperarContrasenaMail;
use Illuminate\Notifications\Notification;

class RecuperarContrasenaNotification extends Notification
{
    public function __construct(
        protected string $token
    ) {}

    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(mixed $notifiable): \Illuminate\Mail\Mailable
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->correo_electronico,
        ], false));

        return (new RecuperarContrasenaMail($notifiable, $resetUrl))
            ->to($notifiable->correo_electronico);
    }
}
