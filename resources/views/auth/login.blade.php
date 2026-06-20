<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Consulado España Maracay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --es-red: #c60b1e; --es-yellow: #ffc400; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f8; min-height: 100vh; display: flex; flex-direction: column; }
        .auth-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .auth-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            display: flex;
        }
        .auth-left {
            background: linear-gradient(160deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            flex: 1;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(198,11,30,0.1);
            border-radius: 50%;
            top: -80px;
            right: -80px;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 180px;
            height: 180px;
            background: rgba(255,196,0,0.08);
            border-radius: 50%;
            bottom: -60px;
            left: -60px;
        }
        .auth-right { flex: 1; padding: 3rem 2.5rem; }
        .es-flag { height: 5px; background: linear-gradient(90deg, var(--es-red) 25%, var(--es-yellow) 25% 75%, var(--es-red) 75%); }
        .brand-icon { width: 60px; height: 60px; background: linear-gradient(135deg, var(--es-red), #e8051a); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: white; box-shadow: 0 8px 25px rgba(198,11,30,0.4); }
        .form-control { border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 0.7rem 1rem; font-size: 0.9rem; }
        .form-control:focus { border-color: var(--es-red); box-shadow: 0 0 0 3px rgba(198,11,30,0.12); }
        .btn-login { background: linear-gradient(135deg, var(--es-red), #e8051a); border: none; border-radius: 12px; padding: 0.8rem; font-weight: 600; font-size: 0.95rem; transition: all 0.2s; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(198,11,30,0.35); }
        .input-group .input-group-text { border-radius: 12px 0 0 12px; background: #f8fafd; border: 1.5px solid #e5e7eb; border-right: none; color: #6b7280; }
        .input-group .form-control { border-radius: 0 12px 12px 0; border-left: none; }
        .input-group .form-control:focus { border-color: var(--es-red); box-shadow: none; }
        .input-group:focus-within .input-group-text { border-color: var(--es-red); }
        @media (max-width: 767.98px) { .auth-left { display: none; } .auth-card { max-width: 480px; } }
    </style>
</head>
<body>
<div class="es-flag"></div>
<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Lado Izquierdo -->
        <div class="auth-left">
            <div class="position-relative z-1">
                <div class="brand-icon mb-4"><i class="bi bi-building-fill"></i></div>
                <h1 class="text-white fw-700 fs-3 mb-2">Consulado Honorario<br>del Reino de España</h1>
                <p class="mb-0" style="color:rgba(255,255,255,0.6);font-size:0.95rem;">Maracay, Estado Aragua<br>Venezuela</p>

                <div class="mt-4 pt-3" style="border-top:1px solid rgba(255,255,255,0.1);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-calendar-check text-white"></i>
                        </div>
                        <div>
                            <p class="text-white mb-0 fw-500" style="font-size:0.85rem;">Agenda tu cita en línea</p>
                            <p style="color:rgba(255,255,255,0.5);font-size:0.75rem;margin:0;">Rápido, simple y seguro</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div style="width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-shield-check text-white"></i>
                        </div>
                        <div>
                            <p class="text-white mb-0 fw-500" style="font-size:0.85rem;">Trámites seguros</p>
                            <p style="color:rgba(255,255,255,0.5);font-size:0.75rem;margin:0;">Pasaporte, Registro Civil y más</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-bell text-white"></i>
                        </div>
                        <div>
                            <p class="text-white mb-0 fw-500" style="font-size:0.85rem;">Notificaciones automáticas</p>
                            <p style="color:rgba(255,255,255,0.5);font-size:0.75rem;margin:0;">Recibes confirmación por correo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lado Derecho - Formulario -->
        <div class="auth-right">
            <div class="mb-4">
                <h2 class="fw-700 mb-1" style="font-size:1.5rem;">Iniciar Sesión</h2>
                <p class="text-muted" style="font-size:0.875rem;">Accede a tu portal consular</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger" style="border-radius:12px;font-size:0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    @foreach($errors->all() as $error) {{ $error }} @endforeach
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success" style="border-radius:12px;font-size:0.85rem;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-600" style="font-size:0.85rem;">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="correo_electronico" class="form-control" placeholder="usuario@correo.com" value="{{ old('correo_electronico') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600" style="font-size:0.85rem;">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                        <button type="button" class="input-group-text" style="border-radius:0 12px 12px 0;cursor:pointer;border:1.5px solid #e5e7eb;border-left:none;" onclick="togglePassword()">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:0.85rem;">Recordarme</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar Sesión
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted" style="font-size:0.85rem;">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" style="color:var(--es-red);font-weight:600;text-decoration:none;">Regístrate aquí</a>
                </p>
            </div>

            <hr class="my-3" style="opacity:0.1;">
            <p class="text-center text-muted mb-0" style="font-size:0.72rem;">
                <i class="bi bi-shield-lock me-1"></i>
                Conexión segura · Sistema Digital Consular © {{ date('Y') }}
            </p>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const pwd = document.getElementById('password');
    const icon = document.getElementById('eyeIcon');
    if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        pwd.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>
</body>
</html>
