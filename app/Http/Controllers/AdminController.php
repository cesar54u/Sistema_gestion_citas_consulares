<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EstadoCitaMail;
use App\Models\Correo;
use App\Models\User;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\Disponibilidad;
use App\Models\HistorialSolicitud;

class AdminController extends Controller
{
    // =============================================
    // DASHBOARD
    // =============================================
    public function index()
    {
        $stats = [
            'total_usuarios'    => User::count(),
            'usuarios'          => User::where('rol', 'usuario')->count(),
            'usuarios_este_mes' => User::where('rol', 'usuario')
                                      ->whereMonth('created_at', now()->month)
                                      ->whereYear('created_at', now()->year)
                                      ->count(),
            'total_citas'       => Cita::count(),
            'citas_pendientes'  => Cita::where('estado', 'pendiente')->count(),
            'citas_aprobadas'   => Cita::where('estado', 'aprobada')->count(),
            'citas_rechazadas'  => Cita::where('estado', 'rechazada')->count(),
            'citas_completadas' => Cita::where('estado', 'completada')->count(),
            'citas_canceladas'  => Cita::where('estado', 'cancelada')->count(),
            'servicios_activos' => Servicio::where('estado', true)->count(),
            'citas_hoy'         => Cita::whereDate('fecha_cita', today())->count(),
        ];

        $citasRecientes = Cita::with(['usuario', 'servicio'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('stats', 'citasRecientes'));
    }

    // =============================================
    // GESTIÓN DE USUARIOS
    // =============================================
    public function usuarios(Request $request)
    {
        $query = User::query();

        if ($request->filled('buscar')) {
            $b = $request->buscar;
            $query->where(function ($q) use ($b) {
                $q->where('nombre', 'like', "%$b%")
                  ->orWhere('apellido', 'like', "%$b%")
                  ->orWhere('cedula', 'like', "%$b%")
                  ->orWhere('correo_electronico', 'like', "%$b%");
            });
        }

        if ($request->filled('rol')) {
            $query->where('rol', $request->rol);
        }

        $usuarios = $query->orderBy('nombre')->paginate(15)->withQueryString();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function crearUsuario()
    {
        return view('admin.usuarios.crear');
    }

    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:100',
            'apellido'           => 'required|string|max:100',
            'cedula'             => 'required|string|max:20|unique:usuarios,cedula',
            'telefono'           => 'nullable|string|max:20',
            'correo_electronico' => 'required|email|max:150|unique:usuarios,correo_electronico',
            'usuario'            => 'required|string|max:50|unique:usuarios,usuario',
            'rol'                => 'required|in:admin,usuario',
            'password'           => 'required|string|min:8|confirmed',
        ]);

        $nuevoUsuario = User::create([
            'nombre'             => $request->nombre,
            'apellido'           => $request->apellido,
            'cedula'             => $request->cedula,
            'telefono'           => $request->telefono,
            'correo_electronico' => $request->correo_electronico,
            'usuario'            => $request->usuario,
            'rol'                => $request->rol,
            'password'           => Hash::make($request->password),
        ]);

        // Enviar correo de verificación si el usuario no es admin
        if ($nuevoUsuario->rol === 'usuario') {
            try {
                event(new \Illuminate\Auth\Events\Registered($nuevoUsuario));
            } catch (\Exception $e) {
                // No fallar si el correo falla
            }
        }

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado exitosamente. Se envió un correo de verificación.');
    }

    public function editarUsuario(User $usuario)
    {
        return view('admin.usuarios.editar', compact('usuario'));
    }

    public function actualizarUsuario(Request $request, User $usuario)
    {
        $request->validate([
            'nombre'             => 'required|string|max:100',
            'apellido'           => 'required|string|max:100',
            'cedula'             => 'required|string|max:20|unique:usuarios,cedula,' . $usuario->id,
            'telefono'           => 'nullable|string|max:20',
            'correo_electronico' => 'required|email|max:150|unique:usuarios,correo_electronico,' . $usuario->id,
            'usuario'            => 'required|string|max:50|unique:usuarios,usuario,' . $usuario->id,
            'rol'                => 'required|in:admin,usuario',
            'password'           => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('nombre', 'apellido', 'cedula', 'telefono', 'correo_electronico', 'usuario', 'rol');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    public function eliminarUsuario(User $usuario)
    {
        if ($usuario->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    // =============================================
    // GESTIÓN DE SERVICIOS
    // =============================================
    public function servicios()
    {
        $servicios = Servicio::withCount('citas')->orderBy('nombre_producto')->paginate(15);
        return view('admin.servicios.index', compact('servicios'));
    }

    public function crearServicio()
    {
        return view('admin.servicios.crear');
    }

    public function guardarServicio(Request $request)
    {
        $request->validate([
            'nombre_producto' => 'required|string|max:150',
            'tipo'            => 'required|string|max:100',
            'duracion'        => 'required|integer|min:5',
            'precio'          => 'required|numeric|min:0',
            'descripcion'     => 'nullable|string',
        ]);

        Servicio::create([
            'nombre_producto' => $request->nombre_producto,
            'tipo'            => $request->tipo,
            'duracion'        => $request->duracion,
            'precio'          => $request->precio,
            'descripcion'     => $request->descripcion,
            'estado'          => $request->boolean('estado'),
        ]);

        return redirect()->route('admin.servicios')->with('success', 'Servicio creado exitosamente.');
    }

    public function editarServicio(Servicio $servicio)
    {
        return view('admin.servicios.editar', compact('servicio'));
    }

    public function actualizarServicio(Request $request, Servicio $servicio)
    {
        $request->validate([
            'nombre_producto' => 'required|string|max:150',
            'tipo'            => 'required|string|max:100',
            'duracion'        => 'required|integer|min:5',
            'precio'          => 'required|numeric|min:0',
            'descripcion'     => 'nullable|string',
        ]);

        $servicio->update([
            'nombre_producto' => $request->nombre_producto,
            'tipo'            => $request->tipo,
            'duracion'        => $request->duracion,
            'precio'          => $request->precio,
            'descripcion'     => $request->descripcion,
            'estado'          => $request->boolean('estado'),
        ]);

        return redirect()->route('admin.servicios')->with('success', 'Servicio actualizado correctamente.');
    }

    public function eliminarServicio(Servicio $servicio)
    {
        $citasActivas = $servicio->citas()
            ->whereIn('estado', ['pendiente', 'aprobada'])
            ->exists();

        if ($citasActivas) {
            return back()->with('error', 'No se puede eliminar el servicio porque tiene citas pendientes o aprobadas asociadas.');
        }

        $servicio->delete();
        return redirect()->route('admin.servicios')->with('success', 'Servicio eliminado.');
    }

    // =============================================
    // GESTIÓN DE DISPONIBILIDAD
    // =============================================
    public function disponibilidad()
    {
        $disponibilidades = Disponibilidad::with('servicio')
            ->orderByRaw("FIELD(dia_semana, 'lunes','martes','miercoles','jueves','viernes','sabado','domingo')")
            ->get();

        $servicios = Servicio::activos()->get();

        return view('admin.disponibilidad.index', compact('disponibilidades', 'servicios'));
    }

    public function guardarDisponibilidad(Request $request)
    {
        $request->validate([
            'dia_semana'  => 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin'    => 'required|date_format:H:i|after:hora_inicio',
            'max_citas'   => 'required|integer|min:1',
            'servicio_id' => 'nullable|exists:servicios,id',
        ]);

        Disponibilidad::create($request->only('servicio_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'max_citas') + ['activo' => true]);

        return redirect()->route('admin.disponibilidad')->with('success', 'Disponibilidad configurada correctamente.');
    }

    public function eliminarDisponibilidad(Disponibilidad $disponibilidad)
    {
        $disponibilidad->delete();
        return redirect()->route('admin.disponibilidad')->with('success', 'Disponibilidad eliminada.');
    }

    // =============================================
    // GESTIÓN DE CITAS
    // =============================================
    public function citas(Request $request)
    {
        $query = Cita::with(['usuario', 'servicio']);

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_cita', $request->fecha);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_cita', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_cita', '<=', $request->fecha_hasta);
        }
        if ($request->filled('servicio_id')) {
            $query->where('servicio_id', $request->servicio_id);
        }

        $citas     = $query->orderByDesc('fecha_cita')->paginate(20)->withQueryString();
        $servicios = Servicio::orderBy('nombre_producto')->get();
        $usuarios  = User::where('rol', 'usuario')->orderBy('nombre')->get();

        return view('admin.citas.index', compact('citas', 'servicios', 'usuarios'));
    }

    public function verCita(Cita $cita)
    {
        $cita->load(['usuario', 'servicio', 'admin', 'historial.admin']);
        return view('admin.citas.ver', compact('cita'));
    }

    public function aprobarCita(Cita $cita)
    {
        $cita->update([
            'estado'             => 'aprobada',
            'admin_id'           => Auth::id(),
            'fecha_modificacion' => now(),
        ]);

        $this->registrarHistorial($cita, 'aprobacion', 'Cita aprobada por el administrador.');
        $this->enviarCorreoEstado($cita);

        return back()->with('success', 'Cita aprobada correctamente. Se notificó al usuario por correo.');
    }

    public function rechazarCita(Request $request, Cita $cita)
    {
        $request->validate(['motivo_rechazo' => 'required|string|max:500']);

        $cita->update([
            'estado'             => 'rechazada',
            'admin_id'           => Auth::id(),
            'motivo_rechazo'     => $request->motivo_rechazo,
            'fecha_modificacion' => now(),
        ]);

        $this->registrarHistorial($cita, 'rechazo', 'Cita rechazada. Motivo: ' . $request->motivo_rechazo);
        $this->enviarCorreoEstado($cita);

        return back()->with('success', 'Cita rechazada. Se notificó al usuario por correo.');
    }

    public function completarCita(Cita $cita)
    {
        $cita->update([
            'estado'             => 'completada',
            'admin_id'           => Auth::id(),
            'fecha_modificacion' => now(),
        ]);

        $this->registrarHistorial($cita, 'completada', 'Cita marcada como completada.');
        $this->enviarCorreoEstado($cita);

        return back()->with('success', 'Cita marcada como completada. Se notificó al usuario.');
    }

    public function reprogramarCita(Request $request, Cita $cita)
    {
        $request->validate([
            'fecha_cita'  => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
        ]);

        $fechaAnterior = $cita->fecha_cita->format('d/m/Y') . ' ' . $cita->hora_inicio;

        $cita->update([
            'fecha_cita'         => $request->fecha_cita,
            'hora_inicio'        => $request->hora_inicio . ':00',
            'estado'             => 'pendiente',
            'admin_id'           => Auth::id(),
            'fecha_modificacion' => now(),
        ]);

        $this->registrarHistorial($cita, 'reprogramacion', "Cita reprogramada desde {$fechaAnterior}.");

        return back()->with('success', 'Cita reprogramada correctamente.');
    }

    // =============================================
    // HISTORIAL DE SOLICITUDES
    // =============================================
    public function historial(Request $request)
    {
        $query = HistorialSolicitud::with(['usuario', 'servicio', 'admin'])
            ->orderByDesc('fecha_modificacion');

        if ($request->filled('usuario_id')) {
            $query->where('usuario_id', $request->usuario_id);
        }
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_cita', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_cita', '<=', $request->fecha_hasta);
        }

        $historial = $query->paginate(20)->withQueryString();
        $usuarios  = User::orderBy('nombre')->get();

        return view('admin.historial', compact('historial', 'usuarios'));
    }

    private function registrarHistorial(Cita $cita, string $accion, string $descripcion): void
    {
        HistorialSolicitud::create([
            'cita_id'           => $cita->id,
            'usuario_id'        => $cita->usuario_id,
            'servicio_id'       => $cita->servicio_id,
            'admin_id'          => Auth::id(),
            'fecha_cita'        => $cita->fecha_cita,
            'hora_inicio'       => $cita->hora_inicio,
            'estado'            => $cita->estado,
            'accion'            => $accion,
            'descripcion'       => $descripcion,
            'fecha_modificacion' => now(),
        ]);
    }

    private function enviarCorreoEstado(Cita $cita): void
    {
        try {
            $cita->load(['usuario', 'servicio']);
            Mail::to($cita->usuario->correo_electronico)->send(new EstadoCitaMail($cita));

            Correo::create([
                'emisor'       => config('mail.from.address'),
                'destinatario' => $cita->usuario->correo_electronico,
                'asunto'       => "Estado de Cita #{$cita->id}: " . ucfirst($cita->estado),
                'cuerpo'       => "Notificación automática de cambio de estado enviada al usuario.",
                'estado'       => 'enviado',
                'intentos'     => 1,
            ]);
        } catch (\Exception $e) {
            Correo::create([
                'emisor'       => config('mail.from.address'),
                'destinatario' => $cita->usuario->correo_electronico ?? 'desconocido',
                'asunto'       => "Estado Cita #{$cita->id} [FALLO]",
                'cuerpo'       => $e->getMessage(),
                'estado'       => 'error',
                'intentos'     => 1,
            ]);
        }
    }
}
