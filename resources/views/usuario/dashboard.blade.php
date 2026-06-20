@extends('layouts.app')
@section('title', 'Dashboard | Portal Consular')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">¡Bienvenido, {{ Auth::user()->nombre }}!</h1>
        <p class="page-breadcrumb">Portal de Citas · Consulado Honorario España Maracay</p>
    </div>
    <a href="{{ route('citas.create') }}" class="btn btn-primary text-white px-4 py-2">
        <i class="bi bi-plus-circle-fill me-2"></i>Agendar Cita
    </a>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $estadisticas['pendientes'] }}</div>
                <div class="stat-label">Pendientes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
            <div>
                <div class="stat-value">{{ $estadisticas['aprobadas'] }}</div>
                <div class="stat-label">Aprobadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-patch-check-fill"></i></div>
            <div>
                <div class="stat-value">{{ $estadisticas['completadas'] }}</div>
                <div class="stat-label">Completadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-x-circle-fill"></i></div>
            <div>
                <div class="stat-value">{{ $estadisticas['rechazadas'] }}</div>
                <div class="stat-label">Rechazadas</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Próximas Citas -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2" style="color:#c60b1e;"></i>Próximas Citas</h6>
                <a href="{{ route('citas.mis-citas') }}" class="btn btn-sm" style="font-size:0.78rem;color:#c60b1e;">Ver todas</a>
            </div>
            <div class="card-body px-4">
                @forelse($proximasCitas as $cita)
                    <div class="d-flex align-items-center gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div style="width:48px;height:48px;background:#f0f2f8;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-calendar3" style="font-size:1.2rem;color:#c60b1e;"></i>
                        </div>
                        <div class="flex-1">
                            <div class="fw-600" style="font-size:0.875rem;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">
                                <i class="bi bi-calendar me-1"></i>{{ $cita->fecha_cita->format('d/m/Y') }}
                                <i class="bi bi-clock ms-2 me-1"></i>{{ substr($cita->hora_inicio, 0, 5) }}
                            </div>
                        </div>
                        <span class="estado-badge badge-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#d1d5db;"></i>
                        <p class="text-muted mt-2 mb-3" style="font-size:0.875rem;">No tienes citas próximas</p>
                        <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm text-white">
                            <i class="bi bi-plus-circle me-1"></i>Agendar Cita
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actividad Reciente -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color:#c60b1e;"></i>Actividad Reciente</h6>
            </div>
            <div class="card-body px-4">
                @forelse($historialReciente as $cita)
                    <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div style="width:8px;height:8px;border-radius:50%;background:{{ $cita->estado == 'aprobada' ? '#059669' : ($cita->estado == 'rechazada' ? '#dc2626' : '#d97706') }};margin-top:6px;flex-shrink:0;"></div>
                        <div>
                            <div style="font-size:0.82rem;font-weight:600;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $cita->fecha_cita->format('d/m/Y') }} · {{ ucfirst($cita->estado) }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <p class="text-muted" style="font-size:0.875rem;">Sin actividad reciente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
