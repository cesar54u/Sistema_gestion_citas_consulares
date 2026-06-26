@extends('layouts.app')
@section('title', 'Gestión de Disponibilidad | Admin')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Disponibilidad Horaria</h1>
    <p class="page-breadcrumb">Configura los bloques de disponibilidad para las citas consulares</p>
</div>

<div class="row g-4">
    <!-- Formulario para agregar disponibilidad -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle-fill me-2" style="color:#c60b1e;"></i>Nuevo Bloque de Disponibilidad</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('admin.disponibilidad.guardar') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Día de la semana *</label>
                            <select name="dia_semana" class="form-select" required>
                                <option value="">Seleccionar día</option>
                                @foreach(\App\Models\Disponibilidad::diasOrdenados() as $dia)
                                    <option value="{{ $dia }}" {{ old('dia_semana') == $dia ? 'selected' : '' }}>
                                        {{ ucfirst($dia) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hora inicio *</label>
                            <input type="time" name="hora_inicio" class="form-control" value="{{ old('hora_inicio') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Hora fin *</label>
                            <input type="time" name="hora_fin" class="form-control" value="{{ old('hora_fin') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Máx. citas por slot *</label>
                            <input type="number" name="max_citas" class="form-control" value="{{ old('max_citas', 8) }}" min="1" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Servicio</label>
                            <select name="servicio_id" class="form-select">
                                <option value="">Todos los servicios</option>
                                @foreach($servicios as $servicio)
                                    <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                        {{ $servicio->nombre_producto }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary text-white w-100">
                            <i class="bi bi-plus-circle me-2"></i>Agregar Disponibilidad
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de disponibilidad existente -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar3 me-2" style="color:#c60b1e;"></i>Bloques Configurados</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Día</th>
                                <th>Horario</th>
                                <th>Máx. Citas</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($disponibilidades as $disp)
                            <tr>
                                <td>
                                    <span class="fw-600" style="font-size:0.875rem;">{{ ucfirst($disp->dia_semana) }}</span>
                                </td>
                                <td style="font-size:0.875rem;">
                                    {{ substr($disp->hora_inicio, 0, 5) }} - {{ substr($disp->hora_fin, 0, 5) }}
                                </td>
                                <td>
                                    <span class="fw-600" style="font-size:0.875rem;">{{ $disp->max_citas }}</span>
                                </td>
                                <td style="font-size:0.82rem;">
                                    @if($disp->servicio)
                                        <span style="padding:0.2rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#ede9fe;color:#5b21b6;">
                                            {{ $disp->servicio->nombre_producto }}
                                        </span>
                                    @else
                                        <span class="text-muted" style="font-size:0.78rem;">Todos</span>
                                    @endif
                                </td>
                                <td>
                                    @if($disp->activo)
                                        <span style="padding:0.2rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#d1fae5;color:#065f46;">Activo</span>
                                    @else
                                        <span style="padding:0.2rem 0.65rem;border-radius:50px;font-size:0.7rem;font-weight:600;background:#f3f4f6;color:#6b7280;">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.disponibilidad.eliminar', $disp) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este bloque de disponibilidad?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.78rem;" type="submit">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-calendar-x" style="font-size:2.5rem;color:#d1d5db;"></i>
                                    <p class="text-muted mt-2">No hay bloques de disponibilidad configurados</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Formulario para agregar fecha bloqueada -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-x-fill me-2" style="color:#c60b1e;"></i>Bloquear Fecha (Feriados / Vacaciones)</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('admin.disponibilidad.fechas.guardar') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Fecha *</label>
                            <input type="date" name="fecha" class="form-control" value="{{ old('fecha') }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motivo (Opcional)</label>
                            <input type="text" name="motivo" class="form-control" value="{{ old('motivo') }}" placeholder="Ej: Día de la Independencia, Mantenimiento...">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-danger text-white w-100">
                            <i class="bi bi-lock-fill me-2"></i>Bloquear Fecha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabla de fechas bloqueadas -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-list-stars me-2" style="color:#c60b1e;"></i>Fechas Bloqueadas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Motivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fechasBloqueadas as $fb)
                            <tr>
                                <td>
                                    <span class="fw-600" style="font-size:0.875rem;">{{ \Carbon\Carbon::parse($fb->fecha)->format('d/m/Y') }}</span>
                                </td>
                                <td style="font-size:0.875rem;">
                                    {{ $fb->motivo ?: 'Sin motivo específico' }}
                                </td>
                                <td>
                                    <form action="{{ route('admin.disponibilidad.fechas.eliminar', $fb->id) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar este bloqueo? La fecha volverá a estar disponible.')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.78rem;" type="submit">
                                            <i class="bi bi-trash"></i> Desbloquear
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    <p class="text-muted mb-0">No hay fechas bloqueadas actualmente</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
