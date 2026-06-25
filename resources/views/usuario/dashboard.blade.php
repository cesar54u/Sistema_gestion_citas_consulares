@extends('layouts.app')
@section('title', 'Dashboard | Portal Consular')

@push('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        border-radius: 20px;
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(198,11,30,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,196,0,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    .user-stat-card {
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
    .user-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .user-stat-card .stat-icon-u {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        transition: transform 0.3s ease;
    }
    .user-stat-card:hover .stat-icon-u {
        transform: scale(1.1);
    }
    .user-stat-card .stat-val {
        font-size: 1.5rem;
        font-weight: 800;
        line-height: 1;
    }
    .user-stat-card .stat-lbl {
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 0.15rem;
        font-weight: 500;
    }
    .servicio-card-user {
        background: white;
        border-radius: 16px;
        padding: 1.25rem;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .servicio-card-user:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }
    .servicio-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .cita-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 0;
        transition: background 0.2s;
    }
    .cita-item:not(:last-child) {
        border-bottom: 1px solid #f3f4f6;
    }
    .cita-date-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        line-height: 1;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .anim-in {
        animation: fadeInUp 0.5s ease forwards;
        opacity: 0;
    }
    .anim-in:nth-child(1) { animation-delay: 0.05s; }
    .anim-in:nth-child(2) { animation-delay: 0.1s; }
    .anim-in:nth-child(3) { animation-delay: 0.15s; }
    .anim-in:nth-child(4) { animation-delay: 0.2s; }
    .anim-in:nth-child(5) { animation-delay: 0.25s; }
</style>
@endpush

@section('content')
<!-- Banner de bienvenida -->
<div class="welcome-banner mb-4">
    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index:1;">
        <div>
            <h2 style="font-weight:800;font-size:1.5rem;margin-bottom:0.25rem;">¡Hola, {{ Auth::user()->nombre }}!</h2>
            <p style="opacity:0.7;font-size:0.88rem;margin-bottom:0;">Bienvenido al Portal de Citas del Consulado Honorario de España</p>
            <div class="d-flex align-items-center gap-2 mt-2">
                <span style="padding:0.3rem 0.8rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:rgba(255,196,0,0.2);color:#ffc400;">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
                </span>
                @if($estadisticas['pendientes'] > 0)
                <span style="padding:0.3rem 0.8rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:rgba(251,191,36,0.2);color:#fbbf24;">
                    <i class="bi bi-bell-fill me-1"></i>{{ $estadisticas['pendientes'] }} pendiente{{ $estadisticas['pendientes'] > 1 ? 's' : '' }}
                </span>
                @endif
            </div>
        </div>
        <a href="{{ route('citas.create') }}" class="btn px-4 py-2 d-none d-md-flex align-items-center gap-2"
           style="background:linear-gradient(135deg,#c60b1e,#e8051a);color:white;border-radius:12px;font-weight:700;font-size:0.88rem;box-shadow:0 4px 15px rgba(198,11,30,0.4);border:none;">
            <i class="bi bi-plus-circle-fill"></i>Agendar Cita
        </a>
    </div>
</div>

<!-- Tarjetas de Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3 anim-in">
        <div class="user-stat-card">
            <div class="stat-icon-u" style="background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706;">
                <i class="bi bi-hourglass-split"></i>
            </div>
            <div>
                <div class="stat-val" style="color:#d97706;">{{ $estadisticas['pendientes'] }}</div>
                <div class="stat-lbl">Pendientes</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 anim-in">
        <div class="user-stat-card">
            <div class="stat-icon-u" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);color:#059669;">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-val" style="color:#059669;">{{ $estadisticas['aprobadas'] }}</div>
                <div class="stat-lbl">Aprobadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 anim-in">
        <div class="user-stat-card">
            <div class="stat-icon-u" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#2563eb;">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <div>
                <div class="stat-val" style="color:#2563eb;">{{ $estadisticas['completadas'] }}</div>
                <div class="stat-lbl">Completadas</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 anim-in">
        <div class="user-stat-card">
            <div class="stat-icon-u" style="background:linear-gradient(135deg,#fee2e2,#fecaca);color:#dc2626;">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div>
                <div class="stat-val" style="color:#dc2626;">{{ $estadisticas['rechazadas'] }}</div>
                <div class="stat-lbl">Rechazadas</div>
            </div>
        </div>
    </div>
</div>

<!-- Botón mobile para agendar -->
<div class="d-md-none mb-4">
    <a href="{{ route('citas.create') }}" class="btn btn-primary text-white w-100 py-2">
        <i class="bi bi-plus-circle-fill me-2"></i>Agendar Nueva Cita
    </a>
</div>

<div class="row g-4 mb-4">
    <!-- Próximas Citas -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2" style="color:#c60b1e;"></i>Próximas Citas</h6>
                <a href="{{ route('citas.mis-citas') }}" class="btn btn-sm" style="font-size:0.78rem;color:#c60b1e;border:1.5px solid #fecaca;border-radius:8px;padding:0.25rem 0.75rem;">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body px-4">
                @forelse($proximasCitas as $cita)
                    <div class="cita-item">
                        <div class="cita-date-box" style="background:{{ $cita->estado === 'aprobada' ? 'linear-gradient(135deg,#d1fae5,#a7f3d0)' : 'linear-gradient(135deg,#fef3c7,#fde68a)' }};">
                            <span style="font-size:1.1rem;font-weight:800;color:{{ $cita->estado === 'aprobada' ? '#059669' : '#d97706' }};">{{ $cita->fecha_cita->format('d') }}</span>
                            <span style="font-size:0.6rem;font-weight:600;color:{{ $cita->estado === 'aprobada' ? '#059669' : '#d97706' }};text-transform:uppercase;">{{ $cita->fecha_cita->locale('es')->isoFormat('MMM') }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="font-size:0.88rem;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.78rem;">
                                <i class="bi bi-clock me-1"></i>{{ substr($cita->hora_inicio, 0, 5) }}
                                <span class="ms-2"><i class="bi bi-hourglass me-1"></i>{{ $cita->servicio->duracion }} min</span>
                            </div>
                        </div>
                        <span class="estado-badge badge-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div style="width:72px;height:72px;background:#f3f4f6;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                            <i class="bi bi-calendar-x" style="font-size:1.8rem;color:#9ca3af;"></i>
                        </div>
                        <p class="text-muted mb-1" style="font-size:0.9rem;font-weight:600;">No tienes citas próximas</p>
                        <p class="text-muted mb-3" style="font-size:0.8rem;">Agenda tu primera cita consular ahora</p>
                        <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm text-white px-4">
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
            <div class="card-header bg-white border-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color:#c60b1e;"></i>Actividad Reciente</h6>
                <a href="{{ route('citas.historial') }}" class="btn btn-sm" style="font-size:0.78rem;color:#c60b1e;">
                    Ver todo
                </a>
            </div>
            <div class="card-body px-4">
                @forelse($historialReciente as $cita)
                    <div class="d-flex gap-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                            background:{{ match($cita->estado) {
                                'aprobada' => '#d1fae5',
                                'rechazada' => '#fee2e2',
                                'completada' => '#dbeafe',
                                'cancelada' => '#f3f4f6',
                                default => '#fef3c7'
                            } }};
                            color:{{ match($cita->estado) {
                                'aprobada' => '#059669',
                                'rechazada' => '#dc2626',
                                'completada' => '#2563eb',
                                'cancelada' => '#6b7280',
                                default => '#d97706'
                            } }};">
                            <i class="bi bi-{{ match($cita->estado) {
                                'aprobada' => 'check-circle',
                                'rechazada' => 'x-circle',
                                'completada' => 'patch-check',
                                'cancelada' => 'dash-circle',
                                default => 'hourglass-split'
                            } }}" style="font-size:0.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:0.82rem;font-weight:600;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">
                                {{ $cita->fecha_cita->format('d/m/Y') }} · {{ ucfirst($cita->estado) }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <p class="text-muted" style="font-size:0.85rem;">Sin actividad reciente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Servicios Disponibles -->
<div class="mb-3 d-flex justify-content-between align-items-center">
    <h6 class="fw-bold mb-0"><i class="bi bi-briefcase-fill me-2" style="color:#c60b1e;"></i>Servicios Consulares Disponibles</h6>
</div>
<div class="row g-3 mb-4">
    @forelse($serviciosDisponibles as $servicio)
    <div class="col-md-6 col-lg-3 anim-in">
        <div class="servicio-card-user">
            @php
                $iconColors = [
                    'Documentación' => ['bg' => '#fee2e2', 'color' => '#c60b1e', 'icon' => 'file-earmark-text-fill'],
                    'Registro'      => ['bg' => '#dbeafe', 'color' => '#1d4ed8', 'icon' => 'journal-text'],
                    'Ciudadanía'    => ['bg' => '#fef3c7', 'color' => '#d97706', 'icon' => 'person-vcard-fill'],
                    'Certificación' => ['bg' => '#d1fae5', 'color' => '#059669', 'icon' => 'award-fill'],
                ];
                $ic = $iconColors[$servicio->tipo] ?? ['bg' => '#ede9fe', 'color' => '#7c3aed', 'icon' => 'briefcase-fill'];
            @endphp
            <div class="d-flex align-items-start gap-3 mb-2">
                <div class="servicio-icon" style="background:{{ $ic['bg'] }};color:{{ $ic['color'] }};">
                    <i class="bi bi-{{ $ic['icon'] }}"></i>
                </div>
                <div>
                    <div class="fw-bold" style="font-size:0.88rem;">{{ $servicio->nombre_producto }}</div>
                    <span style="font-size:0.68rem;padding:0.15rem 0.5rem;border-radius:50px;background:{{ $ic['bg'] }};color:{{ $ic['color'] }};font-weight:600;">{{ $servicio->tipo }}</span>
                </div>
            </div>
            <p class="text-muted mb-2 flex-grow-1" style="font-size:0.78rem;line-height:1.5;">{{ Str::limit($servicio->descripcion, 75) }}</p>
            <div class="d-flex justify-content-between align-items-center mt-auto">
                <div class="d-flex gap-3" style="font-size:0.72rem;color:#6b7280;">
                    <span><i class="bi bi-clock me-1"></i>{{ $servicio->duracion }} min</span>
                    <span><i class="bi bi-tag me-1"></i>{{ $servicio->precio > 0 ? '$' . number_format($servicio->precio, 2) : 'Gratuito' }}</span>
                </div>
            </div>
            <a href="{{ route('citas.create') }}" class="btn btn-sm w-100 mt-3" style="background:#fef2f2;color:#c60b1e;border-radius:10px;font-weight:600;font-size:0.78rem;">
                <i class="bi bi-calendar-plus me-1"></i>Agendar
            </a>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-4">
        <p class="text-muted">No hay servicios disponibles actualmente</p>
    </div>
    @endforelse
</div>
@endsection
