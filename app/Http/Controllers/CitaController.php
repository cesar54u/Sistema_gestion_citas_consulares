<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Disponibilidad;
use App\Models\HistorialSolicitud;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Dashboard del usuario: muestra próximas citas e historial reciente.
     */
    public function index()
    {
        $user = Auth::user();

        $proximasCitas = Cita::where('usuario_id', $user->id)
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->where('fecha_cita', '>=', today())
            ->with('servicio')
            ->orderBy('fecha_cita')
            ->orderBy('hora_inicio')
            ->take(5)
            ->get();

        $historialReciente = Cita::where('usuario_id', $user->id)
            ->with('servicio')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $statsRaw = Cita::selectRaw('estado, count(*) as count')
            ->where('usuario_id', $user->id)
            ->groupBy('estado')
            ->pluck('count', 'estado')
            ->toArray();

        $estadisticas = [
            'total'       => array_sum($statsRaw),
            'pendientes'  => $statsRaw['pendiente'] ?? 0,
            'aprobadas'   => $statsRaw['aprobada'] ?? 0,
            'completadas' => $statsRaw['completada'] ?? 0,
            'rechazadas'  => $statsRaw['rechazada'] ?? 0,
        ];

        $serviciosDisponibles = Servicio::activos()->get();

        return view('usuario.dashboard', compact('proximasCitas', 'historialReciente', 'estadisticas', 'serviciosDisponibles'));
    }

    /**
     * Formulario para crear una nueva cita.
     */
    public function create()
    {
        $servicios = Servicio::activos()->get();
        return view('usuario.citas.crear', compact('servicios'));
    }

    /**
     * Devuelve los horarios disponibles para una fecha y servicio dado (AJAX).
     */
    public function horariosDisponibles(Request $request)
    {
        $request->validate([
            'fecha'       => 'required|date|after_or_equal:today',
            'servicio_id' => 'required|exists:servicios,id',
        ]);

        $fecha      = Carbon::parse($request->fecha);
        $diaSemana  = $this->nombreDiaEspanol($fecha->dayOfWeek);
        $servicioId = $request->servicio_id;

        // Verificar si la fecha está bloqueada (feriado/excepción)
        $bloqueada = \App\Models\FechaBloqueada::where('fecha', $fecha->format('Y-m-d'))->first();
        if ($bloqueada) {
            return response()->json(['horarios' => [], 'mensaje' => 'Esta fecha no está disponible: ' . ($bloqueada->motivo ?: 'Día festivo/inactivo')]);
        }

        // Buscar disponibilidad del día
        $disponibilidades = Disponibilidad::where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->where(function ($query) use ($servicioId) {
                $query->whereNull('servicio_id')->orWhere('servicio_id', $servicioId);
            })
            ->get();

        if ($disponibilidades->isEmpty()) {
            return response()->json(['horarios' => [], 'mensaje' => 'No hay disponibilidad para este día.']);
        }

        $horarios = [];
        foreach ($disponibilidades as $disp) {
            // Generar slots cada 30 min dentro del rango
            $inicio = Carbon::createFromTimeString($disp->hora_inicio);
            $fin    = Carbon::createFromTimeString($disp->hora_fin);

            while ($inicio->copy()->addMinutes(30)->lte($fin)) {
                $slot = $inicio->format('H:i');

                // Contar cuántas citas hay en este slot
                $citasEnSlot = Cita::where('fecha_cita', $fecha->format('Y-m-d'))
                    ->where('hora_inicio', $slot . ':00')
                    ->whereNotIn('estado', ['rechazada', 'cancelada'])
                    ->count();

                if ($citasEnSlot < $disp->max_citas) {
                    $horarios[] = [
                        'hora'       => $slot,
                        'disponible' => true,
                    ];
                }

                $inicio->addMinutes(30);
            }
        }

        return response()->json(['horarios' => $horarios]);
    }

    /**
     * Guarda la nueva cita.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha_cita'  => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'notas'       => 'nullable|string|max:500',
        ], [
            'servicio_id.required' => 'Seleccione un servicio.',
            'fecha_cita.required'  => 'Seleccione una fecha.',
            'fecha_cita.after_or_equal' => 'No se pueden agendar citas en fechas pasadas.',
            'hora_inicio.required' => 'Seleccione un horario.',
        ]);

        // Verificar duplicado del mismo usuario en la misma fecha
        $duplicado = Cita::where('usuario_id', $user->id)
            ->where('fecha_cita', $request->fecha_cita)
            ->whereNotIn('estado', ['rechazada', 'cancelada'])
            ->exists();

        if ($duplicado) {
            return back()->withErrors(['fecha_cita' => 'Ya tienes una cita agendada para esta fecha.'])->withInput();
        }

        // Verificar disponibilidad del slot
        $fecha     = Carbon::parse($request->fecha_cita);
        $diaSemana = $this->nombreDiaEspanol($fecha->dayOfWeek);

        $bloqueada = \App\Models\FechaBloqueada::where('fecha', $fecha->format('Y-m-d'))->first();
        if ($bloqueada) {
            return back()->withErrors(['fecha_cita' => 'Esta fecha no está disponible: ' . ($bloqueada->motivo ?: 'Día festivo/inactivo')])->withInput();
        }

        $disponibilidad = Disponibilidad::where('dia_semana', $diaSemana)
            ->where('activo', true)
            ->where(function ($query) use ($request) {
                $query->whereNull('servicio_id')->orWhere('servicio_id', $request->servicio_id);
            })
            ->first();

        if (!$disponibilidad) {
            return back()->withErrors(['fecha_cita' => 'No hay disponibilidad para el día seleccionado.'])->withInput();
        }

        $citasEnSlot = Cita::where('fecha_cita', $request->fecha_cita)
            ->where('hora_inicio', $request->hora_inicio . ':00')
            ->whereNotIn('estado', ['rechazada', 'cancelada'])
            ->count();

        if ($citasEnSlot >= $disponibilidad->max_citas) {
            return back()->withErrors(['hora_inicio' => 'El horario seleccionado ya no tiene cupo disponible.'])->withInput();
        }

        $cita = Cita::create([
            'usuario_id'  => $user->id,
            'servicio_id' => $request->servicio_id,
            'fecha_cita'  => $request->fecha_cita,
            'hora_inicio' => $request->hora_inicio . ':00',
            'estado'      => 'pendiente',
            'notas'       => $request->notas,
        ]);

        // Guardar en historial
        HistorialSolicitud::create([
            'cita_id'           => $cita->id,
            'usuario_id'        => $user->id,
            'servicio_id'       => $cita->servicio_id,
            'fecha_cita'        => $cita->fecha_cita,
            'hora_inicio'       => $cita->hora_inicio,
            'estado'            => 'pendiente',
            'accion'            => 'creacion',
            'descripcion'       => 'Cita agendada por el usuario.',
            'fecha_modificacion' => now(),
        ]);

        return redirect()->route('citas.mis-citas')->with('success', '¡Cita agendada exitosamente! Está pendiente de aprobación.');
    }

    /**
     * Mis Citas del usuario.
     */
    public function misCitas()
    {
        $citas = Cita::where('usuario_id', Auth::id())
            ->with('servicio')
            ->orderByDesc('fecha_cita')
            ->paginate(10);

        return view('usuario.citas.mis-citas', compact('citas'));
    }

    /**
     * Historial completo del usuario.
     */
    public function historial()
    {
        $historial = HistorialSolicitud::where('usuario_id', Auth::id())
            ->with('servicio')
            ->orderByDesc('fecha_modificacion')
            ->paginate(15);

        return view('usuario.historial', compact('historial'));
    }

    /**
     * Cancelar una cita (por el usuario).
     */
    public function cancel(Cita $cita)
    {
        if ($cita->usuario_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($cita->estado, ['pendiente', 'aprobada'])) {
            return back()->with('error', 'Esta cita no puede ser cancelada.');
        }

        $cita->update([
            'estado'             => 'cancelada',
            'fecha_modificacion' => now(),
        ]);

        HistorialSolicitud::create([
            'cita_id'           => $cita->id,
            'usuario_id'        => $cita->usuario_id,
            'servicio_id'       => $cita->servicio_id,
            'fecha_cita'        => $cita->fecha_cita,
            'hora_inicio'       => $cita->hora_inicio,
            'estado'            => 'cancelada',
            'accion'            => 'cancelacion',
            'descripcion'       => 'Cita cancelada por el usuario.',
            'fecha_modificacion' => now(),
        ]);

        return back()->with('success', 'Cita cancelada correctamente.');
    }

    private function nombreDiaEspanol(int $dayOfWeek): string
    {
        return match($dayOfWeek) {
            1 => 'lunes',
            2 => 'martes',
            3 => 'miercoles',
            4 => 'jueves',
            5 => 'viernes',
            6 => 'sabado',
            0 => 'domingo',
        };
    }
}
