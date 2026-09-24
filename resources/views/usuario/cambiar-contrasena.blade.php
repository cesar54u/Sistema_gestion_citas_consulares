@extends('layouts.app')
@section('title', 'Cambiar Contraseña | Portal Consular')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Cambiar Contraseña</h1>
    <p class="page-breadcrumb">Actualiza tu contraseña de acceso</p>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card p-4">
            <div class="text-center mb-3">
                <div style="width:64px;height:64px;background:linear-gradient(135deg,#0f3460,#16213e);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto;font-size:1.8rem;color:white;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
            </div>
            <h6 class="fw-bold text-center mb-3">Seguridad de la Cuenta</h6>
            <ul class="list-unstyled" style="font-size:0.85rem;color:#6b7280;">
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;margin-top:2px;"></i>
                    <span>Usa al menos 8 caracteres</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;margin-top:2px;"></i>
                    <span>Combina letras, números y símbolos</span>
                </li>
                <li class="d-flex align-items-start gap-2 mb-2">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;margin-top:2px;"></i>
                    <span>No reutilices contraseñas anteriores</span>
                </li>
                <li class="d-flex align-items-start gap-2">
                    <i class="bi bi-check-circle-fill" style="color:#10b981;margin-top:2px;"></i>
                    <span>No compartas tu contraseña con nadie</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-key-fill me-2" style="color:#c60b1e;"></i>Actualizar Contraseña</h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('password.change') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Contraseña Actual</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8fafd;border:1.5px solid #e5e7eb;border-right:none;"><i class="bi bi-lock"></i></span>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••" required style="border-left:none;">
                        </div>
                        @error('current_password')
                            <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-3" style="opacity:0.1;">

                    <div class="mb-3">
                        <label class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8fafd;border:1.5px solid #e5e7eb;border-right:none;"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mínimo 8 caracteres" required style="border-left:none;">
                        </div>
                        @error('password')
                            <div class="text-danger mt-1" style="font-size:0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirmar Nueva Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-radius:10px 0 0 10px;background:#f8fafd;border:1.5px solid #e5e7eb;border-right:none;"><i class="bi bi-key-fill"></i></span>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la nueva contraseña" required style="border-left:none;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary text-white px-4">
                        <i class="bi bi-shield-check me-2"></i>Cambiar Contraseña
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
