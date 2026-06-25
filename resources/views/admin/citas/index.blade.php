@extends('layouts.app')
@section('title', 'Gestión de Citas | Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Gestión de Citas</h1>
        <p class="page-breadcrumb">Administra y gestiona todas las citas consulares</p>
    </div>
    {{-- Botones de exportar --}}
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.export.citas.pdf', request()->query()) }}"
           target="_blank"
           class="btn btn-sm d-flex align-items-center gap-1"
           style="background:#fee2e2;color:#991b1b;border-radius:10px;font-weight:600;font-size:0.82rem;">
            <i class="bi bi-file-earmark-pdf-fill"></i> PDF
        </a>
        <a href="{{ route('admin.export.citas.excel', request()->query()) }}"
           class="btn btn-sm d-flex align-items-center gap-1"
           style="background:#d1fae5;color:#065f46;border-radius:10px;font-weight:600;font-size:0.82rem;">
            <i class="bi bi-file-earmark-excel-fill"></i> Excel
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.citas') }}" class="row g-2 align-items-end">
            {{-- Selector de usuario --}}
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem;">Usuario</label>
                <select name="usuario_id" class="form-select">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nombre_completo }} — {{ $u->cedula }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente"  {{ request('estado') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                    <option value="aprobada"   {{ request('estado') == 'aprobada'   ? 'selected' : '' }}>Aprobada</option>
                    <option value="rechazada"  {{ request('estado') == 'rechazada'  ? 'selected' : '' }}>Rechazada</option>
                    <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada"  {{ request('estado') == 'cancelada'  ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;">Servicio</label>
                <select name="servicio_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" {{ request('servicio_id') == $servicio->id ? 'selected' : '' }}>
                            {{ $servicio->nombre_producto }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-1">
                <label class="form-label" style="font-size:0.8rem;">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white flex-fill"><i class="bi bi-funnel me-1"></i>Filtrar</button>
                <a href="{{ route('admin.citas') }}" class="btn" style="border:1.5px solid #e5e7eb;border-radius:10px;">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabla de Citas -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Solicitada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $cita->id }}</td>
                        <td>
                            <div class="fw-600" style="font-size:0.875rem;">{{ $cita->usuario->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $cita->usuario->cedula }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.875rem;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ $cita->servicio->tipo }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.875rem;">{{ $cita->fecha_cita->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ $cita->fecha_cita->locale('es')->isoFormat('dddd') }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ substr($cita->hora_inicio, 0, 5) }}</td>
                        <td>
                            <span class="estado-badge badge-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                        </td>
                        <td class="text-muted" style="font-size:0.78rem;">{{ $cita->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.citas.ver', $cita) }}" class="btn btn-sm" style="background:#f3f4f6;color:#374151;border-radius:8px;font-size:0.78rem;">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#d1d5db;"></i>
                            <p class="text-muted mt-2">No se encontraron citas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($citas->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-center pb-3">
        {{ $citas->links() }}
    </div>
    @endif
</div>
@endsection
