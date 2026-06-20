@extends('layouts.app')
@section('title', 'Historial | Portal Consular')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Historial de Solicitudes</h1>
    <p class="page-breadcrumb">Todas las acciones realizadas sobre tus citas</p>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Fecha Acción</th>
                        <th>Servicio</th>
                        <th>Fecha Cita</th>
                        <th>Acción</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historial as $item)
                    <tr>
                        <td style="font-size:0.8rem;color:#6b7280;">{{ $item->fecha_modificacion?->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="fw-600" style="font-size:0.875rem;">{{ $item->servicio->nombre_producto }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $item->fecha_cita?->format('d/m/Y') }}</td>
                        <td>
                            @php
                                $accionColors = [
                                    'creacion'      => ['bg' => '#dbeafe', 'color' => '#1e40af'],
                                    'aprobacion'    => ['bg' => '#d1fae5', 'color' => '#065f46'],
                                    'rechazo'       => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                    'cancelacion'   => ['bg' => '#f3f4f6', 'color' => '#374151'],
                                    'reprogramacion'=> ['bg' => '#fef3c7', 'color' => '#92400e'],
                                    'completada'    => ['bg' => '#ede9fe', 'color' => '#5b21b6'],
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
                        <td class="text-muted" style="font-size:0.8rem;">{{ $item->descripcion }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
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
