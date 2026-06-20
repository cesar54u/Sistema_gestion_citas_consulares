@extends('layouts.app')
@section('title', 'Crear Servicio | Admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.servicios') }}" class="btn btn-sm mb-3" style="border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Volver a Servicios
    </a>
    <h1 class="page-title">Crear Servicio Consular</h1>
</div>

<div class="card" style="max-width:650px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.servicios.guardar') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Nombre del Servicio *</label>
                    <input type="text" name="nombre_producto" class="form-control" value="{{ old('nombre_producto') }}" placeholder="Ej: Pasaporte" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tipo *</label>
                    <select name="tipo" class="form-select" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="Documentación" {{ old('tipo') == 'Documentación' ? 'selected' : '' }}>Documentación</option>
                        <option value="Registro" {{ old('tipo') == 'Registro' ? 'selected' : '' }}>Registro</option>
                        <option value="Ciudadanía" {{ old('tipo') == 'Ciudadanía' ? 'selected' : '' }}>Ciudadanía</option>
                        <option value="Certificación" {{ old('tipo') == 'Certificación' ? 'selected' : '' }}>Certificación</option>
                        <option value="Otro" {{ old('tipo') == 'Otro' ? 'selected' : '' }}>Otro</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Duración (min) *</label>
                    <input type="number" name="duracion" class="form-control" value="{{ old('duracion', 30) }}" min="5" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Precio ($)</label>
                    <input type="number" name="precio" class="form-control" value="{{ old('precio', 0) }}" min="0" step="0.01">
                </div>
                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción del servicio...">{{ old('descripcion') }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="estado" id="estado" value="1" {{ old('estado', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="estado">Servicio activo</label>
                    </div>
                </div>
            </div>
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white px-4">
                    <i class="bi bi-plus-circle-fill me-2"></i>Crear Servicio
                </button>
                <a href="{{ route('admin.servicios') }}" class="btn" style="border:1.5px solid #e5e7eb;border-radius:10px;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
