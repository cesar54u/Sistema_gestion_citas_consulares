@extends('layouts.app')
@section('title', 'Detalle de Cita #' . $cita->id . ' | Admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.citas') }}" class="btn btn-sm mb-3" style="border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Volver a Citas
    </a>
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1 class="page-title">Cita #{{ $cita->id }}</h1>
            <p class="page-breadcrumb">Detalle completo de la solicitud de cita</p>
        </div>
        <span class="estado-badge badge-{{ $cita->estado_badge }}" style="font-size:0.85rem;padding:0.4rem 1rem;">
            {{ ucfirst($cita->estado) }}
        </span>
    </div>
</div>

<div class="row g-4">
    <!-- Información de la Cita -->
    <div class="col-lg-7">
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check-fill me-2" style="color:#c60b1e;"></i>Información de la Cita</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f8fafd;border-radius:12px;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;">Servicio Solicitado</div>
                            <div class="fw-bold" style="font-size:0.95rem;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">{{ $cita->servicio->tipo }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3" style="background:#f8fafd;border-radius:12px;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;">Fecha</div>
                            <div class="fw-bold" style="font-size:0.95rem;">{{ $cita->fecha_cita->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">{{ $cita->fecha_cita->locale('es')->isoFormat('dddd') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3" style="background:#f8fafd;border-radius:12px;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;">Hora</div>
                            <div class="fw-bold" style="font-size:0.95rem;">{{ substr($cita->hora_inicio, 0, 5) }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">{{ $cita->servicio->duracion }} min</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f8fafd;border-radius:12px;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;">Precio del Servicio</div>
                            <div class="fw-bold" style="font-size:0.95rem;">
                                {{ $cita->servicio->precio > 0 ? '$' . number_format($cita->servicio->precio, 2) : 'Gratuito' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3" style="background:#f8fafd;border-radius:12px;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;">Fecha de Solicitud</div>
                            <div class="fw-bold" style="font-size:0.95rem;">{{ $cita->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @if($cita->notas)
                    <div class="col-12">
                        <div class="p-3" style="background:#fffbeb;border-radius:12px;border:1px solid #fef3c7;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;"><i class="bi bi-chat-left-text me-1"></i>Notas del Solicitante</div>
                            <div style="font-size:0.875rem;">{{ $cita->notas }}</div>
                        </div>
                    </div>
                    @endif
                    @if($cita->motivo_rechazo)
                    <div class="col-12">
                        <div class="p-3" style="background:#fef2f2;border-radius:12px;border:1px solid #fee2e2;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;"><i class="bi bi-exclamation-triangle me-1"></i>Motivo de Rechazo</div>
                            <div style="font-size:0.875rem;color:#991b1b;">{{ $cita->motivo_rechazo }}</div>
                        </div>
                    </div>
                    @endif
                    @if($cita->admin)
                    <div class="col-12">
                        <div class="p-3" style="background:#f0fdf4;border-radius:12px;border:1px solid #d1fae5;">
                            <div class="text-muted mb-1" style="font-size:0.75rem;"><i class="bi bi-person-check me-1"></i>Gestionada por</div>
                            <div style="font-size:0.875rem;">{{ $cita->admin->nombre_completo }}</div>
                            @if($cita->fecha_modificacion)
                                <div class="text-muted" style="font-size:0.75rem;">{{ $cita->fecha_modificacion->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Acciones sobre la Cita -->
        @if(in_array($cita->estado, ['pendiente', 'aprobada']))
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-gear-fill me-2" style="color:#c60b1e;"></i>Acciones</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex flex-wrap gap-2 mb-4">
                    @if($cita->estado === 'pendiente')
                    <form action="{{ route('admin.citas.aprobar', $cita) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm px-3 py-2" style="background:#d1fae5;color:#065f46;border-radius:10px;font-weight:600;font-size:0.82rem;"
                                onclick="return confirm('¿Aprobar esta cita?')">
                            <i class="bi bi-check-circle-fill me-1"></i>Aprobar
                        </button>
                    </form>
                    @endif

                    @if($cita->estado === 'aprobada')
                    <form action="{{ route('admin.citas.completar', $cita) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm px-3 py-2" style="background:#dbeafe;color:#1e40af;border-radius:10px;font-weight:600;font-size:0.82rem;"
                                onclick="return confirm('¿Marcar esta cita como completada?')">
                            <i class="bi bi-patch-check-fill me-1"></i>Completar
                        </button>
                    </form>
                    @endif

                    <button type="button" class="btn btn-sm px-3 py-2" style="background:#fee2e2;color:#991b1b;border-radius:10px;font-weight:600;font-size:0.82rem;"
                            data-bs-toggle="collapse" data-bs-target="#rechazarForm">
                        <i class="bi bi-x-circle-fill me-1"></i>Rechazar
                    </button>

                    <button type="button" class="btn btn-sm px-3 py-2" style="background:#fef3c7;color:#92400e;border-radius:10px;font-weight:600;font-size:0.82rem;"
                            data-bs-toggle="collapse" data-bs-target="#reprogramarForm">
                        <i class="bi bi-calendar-event me-1"></i>Reprogramar
                    </button>
                </div>

                <!-- Formulario Rechazar -->
                <div class="collapse mb-3" id="rechazarForm">
                    <div class="p-3" style="background:#fef2f2;border-radius:12px;border:1px solid #fee2e2;">
                        <form action="{{ route('admin.citas.rechazar', $cita) }}" method="POST">
                            @csrf
                            <label class="form-label fw-600" style="font-size:0.85rem;">Motivo del rechazo *</label>
                            <textarea name="motivo_rechazo" class="form-control mb-2" rows="3" required
                                      placeholder="Ingrese el motivo por el cual se rechaza esta cita..."></textarea>
                            <button type="submit" class="btn btn-sm px-3 py-2" style="background:#dc2626;color:white;border-radius:10px;font-weight:600;font-size:0.82rem;">
                                <i class="bi bi-x-circle me-1"></i>Confirmar Rechazo
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Formulario Reprogramar -->
                <div class="collapse" id="reprogramarForm">
                    <div class="p-3" style="background:#fffbeb;border-radius:12px;border:1px solid #fef3c7;">
                        <form action="{{ route('admin.citas.reprogramar', $cita) }}" method="POST">
                            @csrf
                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label fw-600" style="font-size:0.85rem;">Nueva fecha *</label>
                                    <input type="date" name="fecha_cita" class="form-control" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-600" style="font-size:0.85rem;">Nueva hora *</label>
                                    <input type="time" name="hora_inicio" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm px-3 py-2" style="background:#d97706;color:white;border-radius:10px;font-weight:600;font-size:0.82rem;">
                                <i class="bi bi-calendar-event me-1"></i>Confirmar Reprogramación
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Panel lateral -->
    <div class="col-lg-5">
        <!-- Datos del Solicitante -->
        <div class="card mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-fill me-2" style="color:#c60b1e;"></i>Datos del Solicitante</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:50px;height:50px;background:linear-gradient(135deg,#c60b1e,#e8051a);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:white;">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:0.95rem;">{{ $cita->usuario->nombre_completo }}</div>
                        <div class="text-muted" style="font-size:0.78rem;">@{{ $cita->usuario->usuario }}</div>
                    </div>
                </div>
                <div style="font-size:0.85rem;">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Cédula</span>
                        <span class="fw-600">{{ $cita->usuario->cedula }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Correo</span>
                        <span class="fw-600">{{ $cita->usuario->correo_electronico }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted">Teléfono</span>
                        <span class="fw-600">{{ $cita->usuario->telefono ?? 'No registrado' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial de la Cita -->
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color:#c60b1e;"></i>Historial de Acciones</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @forelse($cita->historial->sortByDesc('fecha_modificacion') as $item)
                <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                        background:{{ match($item->accion) {
                            'creacion' => '#dbeafe',
                            'aprobacion' => '#d1fae5',
                            'rechazo' => '#fee2e2',
                            'cancelacion' => '#f3f4f6',
                            'reprogramacion' => '#fef3c7',
                            'completada' => '#ede9fe',
                            default => '#f3f4f6'
                        } }};
                        color:{{ match($item->accion) {
                            'creacion' => '#1e40af',
                            'aprobacion' => '#065f46',
                            'rechazo' => '#991b1b',
                            'cancelacion' => '#374151',
                            'reprogramacion' => '#92400e',
                            'completada' => '#5b21b6',
                            default => '#374151'
                        } }};font-size:0.8rem;">
                        <i class="bi bi-{{ match($item->accion) {
                            'creacion' => 'plus-circle',
                            'aprobacion' => 'check-circle',
                            'rechazo' => 'x-circle',
                            'cancelacion' => 'dash-circle',
                            'reprogramacion' => 'calendar-event',
                            'completada' => 'patch-check',
                            default => 'circle'
                        } }}"></i>
                    </div>
                    <div>
                        <div class="fw-600" style="font-size:0.82rem;">{{ ucfirst($item->accion ?? 'Acción') }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">{{ $item->descripcion }}</div>
                        <div class="text-muted" style="font-size:0.7rem;">
                            {{ $item->fecha_modificacion?->format('d/m/Y H:i') }}
                            @if($item->admin)
                                · {{ $item->admin->nombre_completo }}
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <p class="text-muted mb-0" style="font-size:0.85rem;">Sin historial registrado</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
