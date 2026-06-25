@extends('layouts.app')
@section('title', 'Gestión de Correos | Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Gestión de Correos</h1>
        <p class="page-breadcrumb">Historial de correos enviados y herramientas de envío</p>
    </div>
</div>

{{-- Stats Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#d1fae5,#a7f3d0);">
                <i class="bi bi-envelope-check-fill" style="color:#065f46;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#065f46;">{{ $stats['enviados'] }}</div>
                <div class="stat-label">Enviados</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#fee2e2,#fecaca);">
                <i class="bi bi-envelope-x-fill" style="color:#991b1b;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#991b1b;">{{ $stats['error'] }}</div>
                <div class="stat-label">Con Error</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#dbeafe,#bfdbfe);">
                <i class="bi bi-envelope-fill" style="color:#1e40af;"></i>
            </div>
            <div>
                <div class="stat-value" style="color:#1e40af;">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Registros</div>
            </div>
        </div>
    </div>
</div>

{{-- Envío de Recordatorios Masivos --}}
<div class="card mb-4">
    <div class="card-body p-4">
        <h2 style="font-size:1rem; font-weight:700; margin-bottom:0.75rem;">
            <i class="bi bi-send-fill me-2" style="color:var(--es-red);"></i>Enviar Recordatorios Manuales por Fecha
        </h2>
        <p style="font-size:0.85rem; color:#6b7280; margin-bottom:1rem;">
            Envía un recordatorio a todos los usuarios con citas <strong>aprobadas</strong> en la fecha seleccionada.
        </p>
        <form method="POST" action="{{ route('admin.correos.recordatorios') }}" class="row g-2 align-items-end">
            @csrf
            <div class="col-md-5">
                <label class="form-label" style="font-size:0.8rem;">Fecha de las citas</label>
                <input type="date" name="fecha" class="form-control" required
                       min="{{ now()->toDateString() }}"
                       value="{{ now()->toDateString() }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary text-white w-100">
                    <i class="bi bi-envelope-fill me-1"></i> Enviar Recordatorios
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.correos') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" style="font-size:0.8rem;">Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Destinatario o asunto..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem;">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="enviado" {{ request('estado')=='enviado'?'selected':'' }}>Enviado</option>
                    <option value="error"   {{ request('estado')=='error'?'selected':'' }}>Error</option>
                    <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white flex-fill">
                    <i class="bi bi-search me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.correos') }}" class="btn" style="border:1.5px solid #e5e7eb;border-radius:10px;">Limpiar</a>
            </div>
        </form>
    </div>
</div>

{{-- Tabla de correos --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Destinatario</th>
                        <th>Asunto</th>
                        <th>Estado</th>
                        <th>Intentos</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($correos as $correo)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $correo->id }}</td>
                        <td style="font-size:0.85rem;">{{ $correo->destinatario }}</td>
                        <td style="font-size:0.85rem; max-width:250px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $correo->asunto }}
                        </td>
                        <td>
                            @if($correo->estado === 'enviado')
                                <span style="padding:0.25rem 0.75rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:#d1fae5;color:#065f46;">
                                    <i class="bi bi-check-circle-fill me-1"></i>Enviado
                                </span>
                            @elseif($correo->estado === 'error')
                                <span style="padding:0.25rem 0.75rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:#fee2e2;color:#991b1b;">
                                    <i class="bi bi-x-circle-fill me-1"></i>Error
                                </span>
                            @else
                                <span style="padding:0.25rem 0.75rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:#fef3c7;color:#92400e;">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.85rem; text-align:center;">{{ $correo->intentos }}</td>
                        <td class="text-muted" style="font-size:0.78rem;">
                            {{ $correo->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <form action="{{ route('admin.correos.eliminar', $correo) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este registro?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.78rem;" type="submit">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-envelope" style="font-size:2.5rem;color:#d1d5db;"></i>
                            <p class="text-muted mt-2">No hay correos registrados aún</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($correos->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-center pb-3">
        {{ $correos->links() }}
    </div>
    @endif
</div>
@endsection
