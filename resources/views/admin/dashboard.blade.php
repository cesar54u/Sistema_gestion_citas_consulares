@extends('layouts.app')
@section('title', 'Panel Administrativo | Consulado España')

@push('styles')
<style>
    .stat-card-modern {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .stat-card-modern::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        border-radius: 0 0 0 80px;
        opacity: 0.06;
    }
    .stat-card-modern .stat-icon-modern {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    .stat-card-modern:hover .stat-icon-modern {
        transform: scale(1.1);
    }
    .stat-card-modern .stat-value-modern {
        font-size: 1.6rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -0.5px;
    }
    .stat-card-modern .stat-label-modern {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.15rem;
        font-weight: 500;
    }
    .distribution-bar {
        display: flex;
        height: 12px;
        border-radius: 6px;
        overflow: hidden;
        background: #f3f4f6;
    }
    .distribution-bar .bar-segment {
        transition: width 0.6s ease;
        min-width: 2px;
    }
    .distribution-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.75rem;
    }
    .distribution-legend .legend-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        color: #6b7280;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 3px;
        flex-shrink: 0;
    }
    .quick-action-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.1rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: none;
    }
    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .quick-action-btn .action-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-in {
        animation: fadeInUp 0.5s ease forwards;
    }
    .animate-in:nth-child(1) { animation-delay: 0.05s; }
    .animate-in:nth-child(2) { animation-delay: 0.1s; }
    .animate-in:nth-child(3) { animation-delay: 0.15s; }
    .animate-in:nth-child(4) { animation-delay: 0.2s; }
    .animate-in:nth-child(5) { animation-delay: 0.25s; }
    .animate-in:nth-child(6) { animation-delay: 0.3s; }
    .animate-in:nth-child(7) { animation-delay: 0.35s; }
    .animate-in:nth-child(8) { animation-delay: 0.4s; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Panel Administrativo</h1>
        <p class="page-breadcrumb">Consulado Honorario del Reino de España · Maracay, Aragua</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="text-muted" style="font-size:0.78rem;">
            <i class="bi bi-calendar3 me-1"></i>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </span>
    </div>
</div>

<!-- Estadísticas principales -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#dbeafe;color:#1d4ed8;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#1d4ed8;">{{ $stats['usuarios'] }}</div>
                <div class="stat-label-modern">Usuarios Registrados</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#e0e7ff;color:#4338ca;">
                <i class="bi bi-person-plus-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#4338ca;">{{ $stats['usuarios_este_mes'] }}</div>
                <div class="stat-label-modern">Nuevos este mes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#fce7f3;color:#be185d;">
                <i class="bi bi-calendar-range-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#be185d;">{{ $stats['total_citas'] }}</div>
                <div class="stat-label-modern">Total de Citas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#fef3c7;color:#d97706;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#d97706;">{{ $stats['citas_pendientes'] }}</div>
                <div class="stat-label-modern">Citas Pendientes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#d1fae5;color:#059669;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#059669;">{{ $stats['citas_aprobadas'] }}</div>
                <div class="stat-label-modern">Citas Aprobadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#fee2e2;color:#dc2626;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#dc2626;">{{ $stats['citas_rechazadas'] }}</div>
                <div class="stat-label-modern">Citas Rechazadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#dbeafe;color:#2563eb;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#2563eb;">{{ $stats['citas_completadas'] }}</div>
                <div class="stat-label-modern">Completadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-3 animate-in">
        <div class="stat-card-modern">
            <div class="stat-icon-modern" style="background:#ede9fe;color:#7c3aed;">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div>
                <div class="stat-value-modern" style="color:#7c3aed;">{{ $stats['servicios_activos'] }}</div>
                <div class="stat-label-modern">Servicios Disponibles</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Distribución de citas + Acciones rápidas -->
    <div class="col-lg-6">
        <!-- Distribución de estados -->
        <div class="card mb-4">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill me-2" style="color:#c60b1e;"></i>Distribución de Citas</h6>
                @php
                    $total = max($stats['total_citas'], 1);
                    $pctPend = round($stats['citas_pendientes'] / $total * 100, 1);
                    $pctApro = round($stats['citas_aprobadas'] / $total * 100, 1);
                    $pctRech = round($stats['citas_rechazadas'] / $total * 100, 1);
                    $pctComp = round($stats['citas_completadas'] / $total * 100, 1);
                    $pctCanc = round($stats['citas_canceladas'] / $total * 100, 1);
                @endphp
                <div class="distribution-bar">
                    <div class="bar-segment" style="width:{{ $pctPend }}%;background:#f59e0b;" title="Pendientes {{ $pctPend }}%"></div>
                    <div class="bar-segment" style="width:{{ $pctApro }}%;background:#10b981;" title="Aprobadas {{ $pctApro }}%"></div>
                    <div class="bar-segment" style="width:{{ $pctComp }}%;background:#3b82f6;" title="Completadas {{ $pctComp }}%"></div>
                    <div class="bar-segment" style="width:{{ $pctRech }}%;background:#ef4444;" title="Rechazadas {{ $pctRech }}%"></div>
                    <div class="bar-segment" style="width:{{ $pctCanc }}%;background:#9ca3af;" title="Canceladas {{ $pctCanc }}%"></div>
                </div>
                <div class="distribution-legend">
                    <div class="legend-item"><div class="legend-dot" style="background:#f59e0b;"></div>Pendientes ({{ $stats['citas_pendientes'] }})</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#10b981;"></div>Aprobadas ({{ $stats['citas_aprobadas'] }})</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#3b82f6;"></div>Completadas ({{ $stats['citas_completadas'] }})</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#ef4444;"></div>Rechazadas ({{ $stats['citas_rechazadas'] }})</div>
                    <div class="legend-item"><div class="legend-dot" style="background:#9ca3af;"></div>Canceladas ({{ $stats['citas_canceladas'] }})</div>
                </div>
            </div>
        </div>

        <!-- Citas hoy -->
        <div class="card" style="background:linear-gradient(135deg,#1a1a2e,#0f3460);border:none;">
            <div class="card-body p-4 text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:0.78rem;opacity:0.7;text-transform:uppercase;letter-spacing:1px;font-weight:600;">Citas Programadas Hoy</div>
                        <div style="font-size:2.5rem;font-weight:800;line-height:1.2;margin-top:0.25rem;">{{ $stats['citas_hoy'] }}</div>
                        <div style="font-size:0.8rem;opacity:0.6;margin-top:0.25rem;">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}</div>
                    </div>
                    <div style="width:64px;height:64px;background:rgba(255,196,0,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-calendar-day-fill" style="font-size:1.8rem;color:#ffc400;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill me-2" style="color:#ffc400;"></i>Acciones Rápidas</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.citas') }}?estado=pendiente" class="quick-action-btn w-100" style="background:#fef3c7;color:#92400e;">
                            <div class="action-icon" style="background:#fde68a;color:#d97706;">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Revisar Pendientes</div>
                                <div style="font-size:0.72rem;opacity:0.7;">{{ $stats['citas_pendientes'] }} por gestionar</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.usuarios.crear') }}" class="quick-action-btn w-100" style="background:#dbeafe;color:#1e40af;">
                            <div class="action-icon" style="background:#bfdbfe;color:#1d4ed8;">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Nuevo Usuario</div>
                                <div style="font-size:0.72rem;opacity:0.7;">Crear cuenta</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.servicios.crear') }}" class="quick-action-btn w-100" style="background:#ede9fe;color:#5b21b6;">
                            <div class="action-icon" style="background:#ddd6fe;color:#7c3aed;">
                                <i class="bi bi-plus-circle-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Nuevo Servicio</div>
                                <div style="font-size:0.72rem;opacity:0.7;">Agregar servicio</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.disponibilidad') }}" class="quick-action-btn w-100" style="background:#d1fae5;color:#065f46;">
                            <div class="action-icon" style="background:#a7f3d0;color:#059669;">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Horarios</div>
                                <div style="font-size:0.72rem;opacity:0.7;">Configurar disponibilidad</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.usuarios') }}" class="quick-action-btn w-100" style="background:#fce7f3;color:#9d174d;">
                            <div class="action-icon" style="background:#fbcfe8;color:#be185d;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Ver Usuarios</div>
                                <div style="font-size:0.72rem;opacity:0.7;">{{ $stats['total_usuarios'] }} registrados</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.historial') }}" class="quick-action-btn w-100" style="background:#f3f4f6;color:#374151;">
                            <div class="action-icon" style="background:#e5e7eb;color:#4b5563;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div>
                                <div class="fw-bold">Historial</div>
                                <div style="font-size:0.72rem;opacity:0.7;">Ver auditoría</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Citas Recientes -->
<div class="card">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check-fill me-2" style="color:#c60b1e;"></i>Citas Recientes</h6>
        <a href="{{ route('admin.citas') }}" class="btn btn-sm px-3" style="font-size:0.78rem;color:#c60b1e;border:1.5px solid #fecaca;border-radius:8px;">
            Ver todas <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citasRecientes as $cita)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.75rem;color:#4338ca;font-weight:700;">
                                    {{ strtoupper(substr($cita->usuario->nombre, 0, 1) . substr($cita->usuario->apellido, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-600" style="font-size:0.85rem;">{{ $cita->usuario->nombre_completo }}</div>
                                    <div class="text-muted" style="font-size:0.72rem;">{{ $cita->usuario->cedula }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.85rem;">{{ $cita->servicio->nombre_producto }}</td>
                        <td style="font-size:0.85rem;">{{ $cita->fecha_cita->format('d/m/Y') }}</td>
                        <td style="font-size:0.85rem;">{{ substr($cita->hora_inicio, 0, 5) }}</td>
                        <td>
                            <span class="estado-badge badge-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.citas.ver', $cita) }}" class="btn btn-sm" style="background:#f3f4f6;color:#374151;border-radius:8px;font-size:0.78rem;">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#d1d5db;"></i>
                            <p class="text-muted mt-2">No hay citas registradas aún</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
