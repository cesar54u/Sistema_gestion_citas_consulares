@extends('layouts.app')
@section('title', 'Crear Usuario | Admin')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.usuarios') }}" class="btn btn-sm mb-3" style="border:1.5px solid #e5e7eb;border-radius:10px;font-size:0.85rem;">
        <i class="bi bi-arrow-left me-1"></i>Volver a Usuarios
    </a>
    <h1 class="page-title">Crear Nuevo Usuario</h1>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-body p-4">
        <form action="{{ route('admin.usuarios.guardar') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Cédula *</label>
                    <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" placeholder="V-12345678" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="0412-0000000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Correo Electrónico *</label>
                    <input type="email" name="correo_electronico" class="form-control" value="{{ old('correo_electronico') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre de Usuario *</label>
                    <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rol *</label>
                    <select name="rol" class="form-select" required>
                        <option value="usuario" {{ old('rol') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                        <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contraseña *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Confirmar Contraseña *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="mt-4 d-flex flex-wrap gap-2">
                <button type="submit" class="btn btn-primary text-white px-4">
                    <i class="bi bi-person-plus-fill me-2"></i>Crear Usuario
                </button>
                <a href="{{ route('admin.usuarios') }}" class="btn" style="border:1.5px solid #e5e7eb;border-radius:10px;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
