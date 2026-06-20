@extends('layouts.app')
@section('title', 'Panel Administrativo | Consulado España')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Panel Administrativo</h1>
    <p class="page-breadcrumb">Consulado Honorario del Reino de España · Maracay, Aragua</p>
</div>

<!-- Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['usuarios'] }}</div>
                <div class="stat-label">Usuarios</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $stats['citas_pendientes'] }}</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['citas_aprobadas'] }}</div>
                <div class="stat-label">Aprobadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['citas_rechazadas'] }}</div>
                <div class="stat-label">Rechazadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-briefcase-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['servicios_activos'] }}</div>
                <div class="stat-label">Servicios</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3;color:#be185d;"><i class="bi bi-calendar-day-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['citas_hoy'] }}</div>
                <div class="stat-label">Citas Hoy</div>
            </div>
        </div>
    </div>
</div>

<!-- Acciones Rápidas -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill me-2" style="color:#ffc400;"></i>Acciones Rápidas</h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-sm px-3 py-2" style="background:#dbeafe;color:#1d4ed8;border-radius:10px;font-weight:600;font-size:0.82rem;">
                        <i class="bi bi-person-plus-fill me-1"></i>Nuevo Usuario
                    </a>
                    <a href="{{ route('admin.servicios.crear') }}" class="btn btn-sm px-3 py-2" style="background:#ede9fe;color:#7c3aed;border-radius:10px;font-weight:600;font-size:0.82rem;">
                        <i class="bi bi-plus-circle-fill me-1"></i>Nuevo Servicio
                    </a>
                    <a href="{{ route('admin.citas') }}" class="btn btn-sm px-3 py-2" style="background:#fef3c7;color:#d97706;border-radius:10px;font-weight:600;font-size:0.82rem;">
                        <i class="bi bi-calendar-check-fill me-1"></i>Ver Citas Pendientes
                    </a>
                    <a href="{{ route('admin.disponibilidad') }}" class="btn btn-sm px-3 py-2" style="background:#d1fae5;color:#059669;border-radius:10px;font-weight:600;font-size:0.82rem;">
                        <i class="bi bi-clock-fill me-1"></i>Configurar Horarios
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Citas Recientes -->
<div class="card">
    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check-fill me-2" style="color:#c60b1e;"></i>Citas Recientes</h6>
        <a href="{{ route('admin.citas') }}" class="btn btn-sm" style="font-size:0.78rem;color:#c60b1e;">Ver todas →</a>
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
                    @foreach($citasRecientes as $cita)
                    <tr>
                        <td>
                            <div class="fw-600" style="font-size:0.875rem;">{{ $cita->usuario->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $cita->usuario->cedula }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $cita->servicio->nombre_producto }}</td>
                        <td style="font-size:0.875rem;">{{ $cita->fecha_cita->format('d/m/Y') }}</td>
                        <td style="font-size:0.875rem;">{{ substr($cita->hora_inicio, 0, 5) }}</td>
                        <td>
                            <span class="estado-badge badge-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.citas.ver', $cita) }}" class="btn btn-sm" style="background:#f3f4f6;color:#374151;border-radius:8px;font-size:0.78rem;">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
