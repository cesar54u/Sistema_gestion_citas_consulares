@extends('layouts.app')
@section('title', 'Mis Citas | Portal Consular')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Mis Citas</h1>
        <p class="page-breadcrumb">Visualiza y gestiona tus citas consulares</p>
    </div>
    <a href="{{ route('citas.create') }}" class="btn btn-primary text-white px-4 py-2">
        <i class="bi bi-plus-circle-fill me-2"></i>Nueva Cita
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Horario</th>
                        <th>Estado</th>
                        <th>Agendada</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $cita->id }}</td>
                        <td>
                            <div class="fw-600" style="font-size:0.875rem;">{{ $cita->servicio->nombre_producto }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $cita->servicio->tipo }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.875rem;">{{ $cita->fecha_cita->format('d/m/Y') }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $cita->fecha_cita->locale('es')->isoFormat('dddd') }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ substr($cita->hora_inicio, 0, 5) }}</td>
                        <td>
                            <span class="estado-badge badge-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                        </td>
                        <td class="text-muted" style="font-size:0.78rem;">{{ $cita->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if(in_array($cita->estado, ['pendiente', 'aprobada']))
                                <form action="{{ route('citas.cancel', $cita) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?')">
                                    @csrf
                                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.78rem;" type="submit">
                                        <i class="bi bi-x-circle me-1"></i>Cancelar
                                    </button>
                                </form>
                            @elseif($cita->estado == 'rechazada' && $cita->motivo_rechazo)
                                <button class="btn btn-sm" style="background:#fef3c7;color:#92400e;border-radius:8px;font-size:0.78rem;"
                                    data-bs-toggle="tooltip" title="{{ $cita->motivo_rechazo }}">
                                    <i class="bi bi-info-circle me-1"></i>Ver motivo
                                </button>
                            @else
                                <span class="text-muted" style="font-size:0.78rem;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#d1d5db;"></i>
                            <p class="text-muted mt-2 mb-3">No tienes citas registradas</p>
                            <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm text-white">
                                <i class="bi bi-plus-circle me-1"></i>Agendar primera cita
                            </a>
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

@push('scripts')
<script>
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
</script>
@endpush
