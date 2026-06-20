@extends('layouts.app')
@section('title', 'Mi Perfil | Portal Consular')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Mi Perfil</h1>
    <p class="page-breadcrumb">Administra tu información personal</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card text-center p-4">
            <div style="width:80px;height:80px;background:linear-gradient(135deg,#c60b1e,#e8051a);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:2rem;color:white;">
                <i class="bi bi-person-fill"></i>
            </div>
            <h5 class="fw-bold mb-1">{{ $user->nombre_completo }}</h5>
            <p class="text-muted mb-2" style="font-size:0.85rem;">{{ $user->correo_electronico }}</p>
            <span class="badge" style="background:#fef2f2;color:#c60b1e;padding:0.35rem 1rem;border-radius:50px;">
                {{ $user->isAdmin() ? 'Administrador' : 'Usuario' }}
            </span>
            <hr class="my-3">
            <div class="text-start" style="font-size:0.85rem;">
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Cédula</span>
                    <span class="fw-600">{{ $user->cedula }}</span>
                </div>
                <div class="d-flex justify-content-between py-1 border-bottom">
                    <span class="text-muted">Usuario</span>
                    <span class="fw-600">@{{ $user->usuario }}</span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Miembro desde</span>
                    <span class="fw-600">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2" style="color:#c60b1e;"></i>Editar Información</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $user->nombre) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" value="{{ old('apellido', $user->apellido) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $user->telefono) }}" placeholder="0412-0000000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="correo_electronico" class="form-control" value="{{ old('correo_electronico', $user->correo_electronico) }}" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary text-white px-4">
                            <i class="bi bi-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
