<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EstadoCitaMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $asunto;

    public function __construct(public Cita $cita)
    {
        $this->asunto = match($cita->estado) {
            'aprobada'   => '✅ Cita Aprobada - Consulado Honorario España | Maracay',
            'rechazada'  => '❌ Cita Rechazada - Consulado Honorario España | Maracay',
            'completada' => '🎉 Cita Completada - Consulado Honorario España | Maracay',
            'cancelada'  => '🚫 Cita Cancelada - Consulado Honorario España | Maracay',
            default      => '📋 Actualización de tu Cita - Consulado Honorario España | Maracay',
        };
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->asunto);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.estado-cita',
            with: [
                'cita'    => $this->cita,
                'usuario' => $this->cita->usuario,
                'servicio'=> $this->cita->servicio,
            ],
        );
    }
}
