@extends('layouts.app')
@section('title', 'Gestión de Usuarios | Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">Gestión de Usuarios</h1>
        <p class="page-breadcrumb">CRUD completo de usuarios del sistema</p>
    </div>
    <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-primary text-white px-4 py-2">
        <i class="bi bi-person-plus-fill me-2"></i>Nuevo Usuario
    </a>
</div>

<!-- Filtros -->
<div class="card mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('admin.usuarios') }}" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label" style="font-size:0.8rem;">Buscar</label>
                <input type="text" name="buscar" class="form-control" placeholder="Nombre, cédula o correo..." value="{{ request('buscar') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-size:0.8rem;">Rol</label>
                <select name="rol" class="form-select">
                    <option value="">Todos los roles</option>
                    <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="usuario" {{ request('rol') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white flex-fill"><i class="bi bi-search me-1"></i>Buscar</button>
                <a href="{{ route('admin.usuarios') }}" class="btn" style="border:1.5px solid #e5e7eb;border-radius:10px;">Limpiar</a>
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
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Cédula</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td class="text-muted" style="font-size:0.8rem;">{{ $usuario->id }}</td>
                        <td>
                            <div class="fw-600" style="font-size:0.875rem;">{{ $usuario->nombre_completo }}</div>
                            <div class="text-muted" style="font-size:0.75rem;">@{{ $usuario->usuario }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $usuario->cedula }}</td>
                        <td style="font-size:0.875rem;">{{ $usuario->correo_electronico }}</td>
                        <td style="font-size:0.875rem;">{{ $usuario->telefono ?? '-' }}</td>
                        <td>
                            @if($usuario->isAdmin())
                                <span style="padding:0.25rem 0.75rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:#fee2e2;color:#c60b1e;">Administrador</span>
                            @else
                                <span style="padding:0.25rem 0.75rem;border-radius:50px;font-size:0.72rem;font-weight:600;background:#dbeafe;color:#1d4ed8;">Usuario</span>
                            @endif
                        </td>
                        <td class="text-muted" style="font-size:0.78rem;">{{ $usuario->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.usuarios.editar', $usuario) }}" class="btn btn-sm" style="background:#dbeafe;color:#1d4ed8;border-radius:8px;font-size:0.78rem;">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($usuario->id !== Auth::id())
                                <form action="{{ route('admin.usuarios.eliminar', $usuario) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario {{ $usuario->nombre }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fee2e2;color:#dc2626;border-radius:8px;font-size:0.78rem;" type="submit">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-people" style="font-size:2.5rem;color:#d1d5db;"></i>
                            <p class="text-muted mt-2">No se encontraron usuarios</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer bg-white border-0 d-flex justify-content-center pb-3">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>
@endsection
