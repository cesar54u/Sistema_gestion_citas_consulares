@extends('layouts.app')
@section('title', 'Historial de Solicitudes | Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Historial de Solicitudes</h1>
        <p class="page-breadcrumb">Registro completo de todas las acciones realizadas sobre citas</p>
    </div>
    {{-- Botones de exportar --}}
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.export.historial.pdf', request()->query()) }}"
           target="_blank"
           class="btn btn-sm d-flex align-items-center gap-1"
           style="background:#fee2e2;color:#991b1b;border-radius:10px;font-weight:600;font-size:0.82rem;">
            <i class="bi bi-file-earmark-pdf-fill"></i> PDF
        </a>
        <a href="{{ route('admin.export.historial.excel', request()->query()) }}"
           class="btn btn-sm d-flex align-items-center gap-1"
           style="background:#d1fae5;color:#065f46;border-radius:10px;font-weight:600;font-size:0.82rem;">
            <i class="bi bi-file-earmark-excel-fill"></i> Excel
        </a>
    </div>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.historial') }}" class="row g-2 align-items-end">
            {{-- Selector de usuario --}}
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem;">Usuario</label>
                <select name="usuario_id" class="form-select" id="selectUsuario">
                    <option value="">Todos los usuarios</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->nombre_completo }} — {{ $u->cedula }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Acción --}}
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;">Acción</label>
                <select name="accion" class="form-select">
                    <option value="">Todas</option>
                    <option value="creacion"       {{ request('accion')=='creacion'       ?'selected':'' }}>Creación</option>
                    <option value="aprobacion"     {{ request('accion')=='aprobacion'     ?'selected':'' }}>Aprobación</option>
                    <option value="rechazo"        {{ request('accion')=='rechazo'        ?'selected':'' }}>Rechazo</option>
                    <option value="reprogramacion" {{ request('accion')=='reprogramacion' ?'selected':'' }}>Reprogramación</option>
                    <option value="completada"     {{ request('accion')=='completada'     ?'selected':'' }}>Completada</option>
                    <option value="cancelacion"    {{ request('accion')=='cancelacion'    ?'selected':'' }}>Cancelación</option>
                </select>
            </div>
            {{-- Rango de fechas --}}
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;">Desde</label>
                <input type="date" name="fecha_desde" class="form-control" value="{{ request('fecha_desde') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" style="font-size:0.8rem;">Hasta</label>
                <input type="date" name="fecha_hasta" class="form-control" value="{{ request('fecha_hasta') }}">
            </div>
            {{-- Acciones --}}
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white flex-fill">
                    <i class="bi bi-funnel me-1"></i>Filtrar
                </button>
                <a href="{{ route('admin.historial') }}" class="btn" style="border:1.5px solid #e5e7eb;border-radius:10px;">Limpiar</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Servicio</th>
                        <th>Fecha Cita</th>
                        <th>Acción</th>
                        <th>Estado</th>
                        <th>Admin</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $item)
                    <tr>
                        <td class="text-muted" style="font-size:0.78rem;">{{ $item->fecha_modificacion?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-600" style="font-size:0.85rem;">{{ $item->usuario->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:0.72rem;">{{ $item->usuario->cedula }}</div>
                        </td>
                        <td style="font-size:0.85rem;">{{ $item->servicio->nombre_producto }}</td>
                        <td style="font-size:0.85rem;">{{ $item->fecha_cita?->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $accionColors = [
                                    'creacion'       => ['bg' => '#dbeafe', 'color' => '#1e40af'],
                                    'aprobacion'     => ['bg' => '#d1fae5', 'color' => '#065f46'],
                                    'rechazo'        => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                    'cancelacion'    => ['bg' => '#f3f4f6', 'color' => '#374151'],
                                    'reprogramacion' => ['bg' => '#fef3c7', 'color' => '#92400e'],
                                    'completada'     => ['bg' => '#ede9fe', 'color' => '#5b21b6'],
                                ];
                                $c = $accionColors[$item->accion] ?? ['bg' => '#f3f4f6', 'color' => '#374151'];
                            @endphp
                            <span style="padding:0.25rem 0.65rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:{{ $c['bg'] }};color:{{ $c['color'] }};">
                                {{ ucfirst($item->accion ?? '-') }}
                            </span>
                        </td>
                        <td>
                            <span class="estado-badge badge-{{ match($item->estado) {
                                'pendiente' => 'pendiente',
                                'aprobada' => 'aprobada',
                                'rechazada' => 'rechazada',
                                'completada' => 'completada',
                                default => 'cancelada'
                            } }}">{{ ucfirst($item->estado) }}</span>
                        </td>
                        <td style="font-size:0.82rem;">
                            @if($item->admin)
                                <span class="fw-600">{{ $item->admin->nombre_completo }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:0.78rem;max-width:200px;">{{ Str::limit($item->descripcion, 60) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-clock-history" style="font-size:2.5rem;color:#d1d5db;"></i>
                            <p class="text-muted mt-2">Sin historial de solicitudes</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($historial->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-center pb-3">
        {{ $historial->links() }}
    </div>
    @endif
</div>
@endsection
