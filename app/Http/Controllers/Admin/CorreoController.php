<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EstadoCitaMail;
use App\Mail\RecordatorioCitaMail;
use App\Models\Cita;
use App\Models\Correo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CorreoController extends Controller
{
    // Lista todos los correos registrados
    public function index(Request $request)
    {
        $query = Correo::query()->orderByDesc('created_at');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('destinatario', 'like', "%$b%")
                  ->orWhere('asunto', 'like', "%$b%");
            });
        }

        $correos = $query->paginate(20)->withQueryString();

        $stats = [
            'enviados' => Correo::where('estado', 'enviado')->count(),
            'error'    => Correo::where('estado', 'error')->count(),
            'total'    => Correo::count(),
        ];

        return view('admin.correos.index', compact('correos', 'stats'));
    }

    // Enviar recordatorio manual a todos con cita mañana
    public function enviarRecordatoriosMasivos(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
        ]);

        $citas = Cita::with(['usuario', 'servicio'])
            ->where('estado', 'aprobada')
            ->whereDate('fecha_cita', $request->fecha)
            ->get();

        if ($citas->isEmpty()) {
            return back()->with('error', 'No hay citas aprobadas para esa fecha.');
        }

        $enviados = 0;
        foreach ($citas as $cita) {
            try {
                Mail::to($cita->usuario->correo_electronico)
                    ->send(new RecordatorioCitaMail($cita, 24));

                Correo::create([
                    'emisor'       => config('mail.from.address'),
                    'destinatario' => $cita->usuario->correo_electronico,
                    'asunto'       => "Recordatorio Manual - Cita #{$cita->id} - {$cita->fecha_cita->format('d/m/Y')}",
                    'cuerpo'       => "Recordatorio manual enviado por el administrador.",
                    'estado'       => 'enviado',
                    'intentos'     => 1,
                ]);
                $enviados++;
            } catch (\Exception $e) {
                Correo::create([
                    'emisor'       => config('mail.from.address'),
                    'destinatario' => $cita->usuario->correo_electronico,
                    'asunto'       => "Recordatorio Manual - Cita #{$cita->id} [FALLO]",
                    'cuerpo'       => $e->getMessage(),
                    'estado'       => 'error',
                    'intentos'     => 1,
                ]);
            }
        }

        return back()->with('success', "Se enviaron {$enviados} de {$citas->count()} recordatorios correctamente.");
    }

    // Enviar correo individual de estado de una cita
    public function enviarEstadoCita(Cita $cita)
    {
        try {
            $cita->load(['usuario', 'servicio']);
            Mail::to($cita->usuario->correo_electronico)->send(new EstadoCitaMail($cita));

            Correo::create([
                'emisor'       => config('mail.from.address'),
                'destinatario' => $cita->usuario->correo_electronico,
                'asunto'       => "Notificación estado Cita #{$cita->id}: {$cita->estado}",
                'cuerpo'       => "Correo de estado enviado manualmente por el administrador.",
                'estado'       => 'enviado',
                'intentos'     => 1,
            ]);

            return back()->with('success', 'Correo de notificación enviado al usuario.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    // Eliminar registro de correo
    public function eliminar(Correo $correo)
    {
        $correo->delete();
        return back()->with('success', 'Registro de correo eliminado.');
    }
}
