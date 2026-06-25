@extends('layouts.app')
@section('title', 'Configuración de Correos | Admin')

@section('content')
<div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
    <div>
        <h1 class="page-title">Configuración de Correos</h1>
        <p class="page-breadcrumb">
            <a href="{{ route('admin.correos') }}" class="text-decoration-none text-muted">Gestión de Correos</a> / Configuración SMTP
        </p>
    </div>
    <a href="{{ route('admin.correos') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Volver a Correos
    </a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('admin.correos.configuracion.guardar') }}" method="POST">
                    @csrf
                    <h5 class="mb-3 text-primary"><i class="bi bi-gear-fill me-2"></i> Configuración de Cuenta (Gmail)</h5>
                    <p class="text-muted small mb-4">Ingresa el correo y la contraseña de aplicación desde la cual se enviarán todos los recordatorios y correos del sistema.</p>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Correo Remitente (Gmail)</label>
                            <input type="email" name="smtp_username" class="form-control" 
                                   value="{{ old('smtp_username', $config->smtp_username) }}" 
                                   placeholder="ejemplo@gmail.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Contraseña de Aplicación</label>
                            <input type="text" name="smtp_password" class="form-control" 
                                   value="{{ old('smtp_password', $config->smtp_password) }}" 
                                   placeholder="16 caracteres (sin espacios)">
                            <div class="form-text">La contraseña de aplicación que generaste en Google Account.</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dirección "De" (From Address)</label>
                            <input type="email" name="from_address" class="form-control" 
                                   value="{{ old('from_address', $config->from_address) }}" 
                                   placeholder="Suele ser igual al correo remitente">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre Remitente</label>
                            <input type="text" name="from_name" class="form-control" 
                                   value="{{ old('from_name', $config->from_name) }}" 
                                   placeholder="Consulado España Maracay">
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <h5 class="mb-3 text-primary"><i class="bi bi-envelope-paper-fill me-2"></i> Personalización de Plantillas</h5>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Texto base del Recordatorio de Citas</label>
                        <textarea name="cuerpo_recordatorio" rows="4" class="form-control">{{ old('cuerpo_recordatorio', $config->cuerpo_recordatorio) }}</textarea>
                        <div class="form-text">Este texto aparecerá en el correo que se envía 24h y 2h antes de la cita. Puedes incluir detalles importantes de la sede o indicaciones generales.</div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm text-white">
                            <i class="bi bi-save me-1"></i> Guardar Configuración
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
