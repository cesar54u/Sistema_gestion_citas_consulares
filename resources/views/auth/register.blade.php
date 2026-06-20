<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | Consulado España Maracay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --es-red: #c60b1e; --es-yellow: #ffc400; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f8; min-height: 100vh; }
        .auth-container { max-width: 820px; margin: 0 auto; padding: 2rem 1rem; }
        .auth-card { background: white; border-radius: 24px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); overflow: hidden; }
        .auth-header { background: linear-gradient(160deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 2rem 2.5rem; }
        .auth-body { padding: 2.5rem; }
        .es-flag { height: 5px; background: linear-gradient(90deg, var(--es-red) 25%, var(--es-yellow) 25% 75%, var(--es-red) 75%); }
        .form-control, .form-select { border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 0.7rem 1rem; font-size: 0.875rem; }
        .form-control:focus, .form-select:focus { border-color: var(--es-red); box-shadow: 0 0 0 3px rgba(198,11,30,0.12); }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #374151; margin-bottom: 0.4rem; }
        .btn-register { background: linear-gradient(135deg, var(--es-red), #e8051a); border: none; border-radius: 12px; padding: 0.8rem; font-weight: 600; color: white; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(198,11,30,0.35); }
        .section-title { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: var(--es-red); font-weight: 700; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 2px solid #fef2f2; }
        .input-icon-group { position: relative; }
        .input-icon-group i { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .input-icon-group .form-control { padding-left: 2.5rem; }
    </style>
</head>
<body>
<div class="es-flag"></div>

<div class="auth-container">
    <!-- Header -->
    <div class="auth-card">
        <div class="auth-header d-flex align-items-center gap-3">
            <div style="width:50px;height:50px;background:linear-gradient(135deg,var(--es-red),#e8051a);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:white;box-shadow:0 6px 20px rgba(198,11,30,0.4);">
                <i class="bi bi-building-fill"></i>
            </div>
            <div>
                <h1 class="text-white fw-bold mb-0" style="font-size:1.3rem;">Crear Cuenta</h1>
                <p class="mb-0" style="color:rgba(255,255,255,0.6);font-size:0.8rem;">Consulado Honorario del Reino de España · Maracay</p>
            </div>
        </div>

        <div class="auth-body">
            @if($errors->any())
                <div class="alert alert-danger mb-4" style="border-radius:12px;font-size:0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Datos Personales -->
                <div class="section-title"><i class="bi bi-person me-1"></i>Datos Personales</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nombre *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-person"></i>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" placeholder="Tu nombre" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Apellido *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-person"></i>
                            <input type="text" name="apellido" class="form-control" value="{{ old('apellido') }}" placeholder="Tu apellido" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cédula / Pasaporte *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-card-text"></i>
                            <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" placeholder="V-12345678" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Teléfono</label>
                        <div class="input-icon-group">
                            <i class="bi bi-telephone"></i>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="0412-0000000">
                        </div>
                    </div>
                </div>

                <!-- Datos de Acceso -->
                <div class="section-title"><i class="bi bi-shield-lock me-1"></i>Datos de Acceso</div>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Correo Electrónico *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="correo_electronico" class="form-control" value="{{ old('correo_electronico') }}" placeholder="correo@ejemplo.com" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nombre de Usuario *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-at"></i>
                            <input type="text" name="usuario" class="form-control" value="{{ old('usuario') }}" placeholder="mi_usuario" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contraseña *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirmar Contraseña *</label>
                        <div class="input-icon-group">
                            <i class="bi bi-lock-fill"></i>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" required>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 align-items-center">
                    <button type="submit" class="btn btn-register px-4 py-2">
                        <i class="bi bi-person-plus-fill me-2"></i>Crear Cuenta
                    </button>
                    <a href="{{ route('login') }}" class="text-muted" style="font-size:0.875rem;text-decoration:none;">
                        ¿Ya tienes cuenta? <span style="color:var(--es-red);font-weight:600;">Inicia sesión</span>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
