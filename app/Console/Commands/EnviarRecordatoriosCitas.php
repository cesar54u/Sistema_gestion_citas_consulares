<?php

namespace App\Console\Commands;

use App\Mail\RecordatorioCitaMail;
use App\Models\Cita;
use App\Models\Correo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature   = 'citas:recordatorios {--horas=24 : Horas de anticipación para el recordatorio}';
    protected $description = 'Envía recordatorios automáticos por correo a usuarios con citas próximas.';

    public function handle(): int
    {
        $horas = (int) $this->option('horas');

        $desde = now()->addHours($horas)->startOfHour();
        $hasta = now()->addHours($horas)->endOfHour();

        $citas = Cita::with(['usuario', 'servicio'])
            ->where('estado', 'aprobada')
            ->whereBetween(\DB::raw("TIMESTAMP(fecha_cita, hora_inicio)"), [$desde, $hasta])
            ->get();

        if ($citas->isEmpty()) {
            $this->info("No hay citas aprobadas en las próximas {$horas} hora(s).");
            return self::SUCCESS;
        }

        $this->info("Enviando recordatorios a {$citas->count()} usuario(s)...");
        $enviados = 0;

        foreach ($citas as $cita) {
            try {
                $correoDestino = $cita->usuario->correo_electronico;

                Mail::to($correoDestino)->send(new RecordatorioCitaMail($cita, $horas));

                // Registrar en tabla correos
                Correo::create([
                    'emisor'       => config('mail.from.address'),
                    'destinatario' => $correoDestino,
                    'asunto'       => "Recordatorio de Cita #{$cita->id} - {$horas}h antes",
                    'cuerpo'       => "Recordatorio automático enviado para la cita del {$cita->fecha_cita->format('d/m/Y')} a las {$cita->hora_inicio}.",
                    'estado'       => 'enviado',
                    'intentos'     => 1,
                ]);

                $enviados++;
                $this->line("  ✅ Recordatorio enviado a: {$correoDestino}");

            } catch (\Exception $e) {
                Log::error("Error enviando recordatorio cita #{$cita->id}: " . $e->getMessage());

                Correo::create([
                    'emisor'       => config('mail.from.address'),
                    'destinatario' => $cita->usuario->correo_electronico ?? 'desconocido',
                    'asunto'       => "Recordatorio de Cita #{$cita->id} [FALLO]",
                    'cuerpo'       => $e->getMessage(),
                    'estado'       => 'error',
                    'intentos'     => 1,
                ]);

                $this->error("  ❌ Error con cita #{$cita->id}: " . $e->getMessage());
            }
        }

        $this->info("Recordatorios enviados: {$enviados}/{$citas->count()}");
        return self::SUCCESS;
    }
}
