<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecordatorioCitaMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Cita $cita, public int $horasAntes = 24) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 Recordatorio de Cita - Consulado Honorario España | Maracay',
        );
    }

    public function content(): Content
    {
        $config = \App\Models\ConfiguracionCorreo::first();
        $mensajePersonalizado = $config ? $config->cuerpo_recordatorio : null;

        return new Content(
            markdown: 'emails.recordatorio-cita',
            with: [
                'cita'       => $this->cita,
                'usuario'    => $this->cita->usuario,
                'servicio'   => $this->cita->servicio,
                'horasAntes' => $this->horasAntes,
                'mensajePersonalizado' => $mensajePersonalizado,
            ],
        );
    }
}
