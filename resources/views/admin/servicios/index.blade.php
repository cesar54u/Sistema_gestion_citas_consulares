@extends('layouts.app')
@section('title', 'Gestión de Servicios | Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Servicios Consulares</h1>
        <p class="page-breadcrumb">Gestiona los servicios ofrecidos por el consulado</p>
    </div>
    <a href="{{ route('admin.servicios.crear') }}" class="btn btn-primary text-white px-4 py-2">
        <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Servicio
    </a>
</div>

<div class="row g-3">
    @forelse($servicios as $servicio)
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge" style="background:#fef2f2;color:#c60b1e;font-size:0.72rem;">{{ $servicio->tipo }}</span>
                    @if($servicio->estado)
                        <span style="padding:0.2rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#d1fae5;color:#065f46;">Activo</span>
                    @else
                        <span style="padding:0.2rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#f3f4f6;color:#6b7280;">Inactivo</span>
                    @endif
                </div>
                <h6 class="fw-bold mb-2">{{ $servicio->nombre_producto }}</h6>
                <p class="text-muted mb-3" style="font-size:0.8rem;line-height:1.5;">{{ Str::limit($servicio->descripcion, 100) }}</p>
                <div class="d-flex gap-3 mb-3" style="font-size:0.78rem;color:#6b7280;">
                    <span><i class="bi bi-clock me-1"></i>{{ $servicio->duracion }} min</span>
                    <span><i class="bi bi-tag me-1"></i>{{ $servicio->precio > 0 ? '$' . number_format($servicio->precio, 2) : 'Gratuito' }}</span>
                    <span><i class="bi bi-calendar-check me-1"></i>{{ $servicio->citas_count }} citas</span>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.servicios.editar', $servicio) }}" class="btn btn-sm flex-fill" style="background:#dbeafe;color:#1d4ed8;border-radius:8px;font-size:0.8rem;">
                        <i class="bi bi-pencil me-1"></i>Editar
                    </a>
                    <form action="{{ route('admin.servicios.eliminar', $servicio) }}" method="POST" onsubmit="return confirm('¿Eliminar servicio?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.8rem;" type="submit">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-briefcase" style="font-size:3rem;color:#d1d5db;"></i>
        <p class="text-muted mt-2">No hay servicios registrados</p>
        <a href="{{ route('admin.servicios.crear') }}" class="btn btn-primary btn-sm text-white">Crear primer servicio</a>
    </div>
    @endforelse
</div>

@if($servicios->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $servicios->links() }}
</div>
@endif
@endsection
