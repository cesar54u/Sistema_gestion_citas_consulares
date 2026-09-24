<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | Consulado España Maracay</title>
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
            max-width: 520px;
            padding: 3rem 2.5rem;
        }
        .es-flag { height: 5px; background: linear-gradient(90deg, var(--es-red) 25%, var(--es-yellow) 25% 75%, var(--es-red) 75%); }
        .brand-icon { width: 60px; height: 60px; background: linear-gradient(135deg, var(--es-red), #e8051a); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; color: white; box-shadow: 0 8px 25px rgba(198,11,30,0.4); margin: 0 auto 1.5rem; }
        .form-control { border-radius: 12px; border: 1.5px solid #e5e7eb; padding: 0.7rem 1rem; font-size: 0.9rem; }
        .form-control:focus { border-color: var(--es-red); box-shadow: 0 0 0 3px rgba(198,11,30,0.12); }
        .btn-submit { background: linear-gradient(135deg, var(--es-red), #e8051a); border: none; border-radius: 12px; padding: 0.8rem; font-weight: 600; font-size: 0.95rem; transition: all 0.2s; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(198,11,30,0.35); }
        .input-group .input-group-text { border-radius: 12px 0 0 12px; background: #f8fafd; border: 1.5px solid #e5e7eb; border-right: none; color: #6b7280; }
        .input-group .form-control { border-radius: 0 12px 12px 0; border-left: none; }
        .input-group .form-control:focus { border-color: var(--es-red); box-shadow: none; }
        .input-group:focus-within .input-group-text { border-color: var(--es-red); }
    </style>
</head>
<body>
<div class="es-flag"></div>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="text-center">
            <div class="brand-icon"><i class="bi bi-key-fill"></i></div>
            <h2 class="fw-700 mb-1" style="font-size:1.5rem;">¿Olvidaste tu contraseña?</h2>
            <p class="text-muted mb-4" style="font-size:0.875rem;">Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.</p>
        </div>

        @if(session('status'))
            <div class="alert alert-success" style="border-radius:12px;font-size:0.85rem;">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="border-radius:12px;font-size:0.85rem;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                @foreach($errors->all() as $error) {{ $error }} @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label fw-600" style="font-size:0.85rem;">Correo Electrónico</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="correo_electronico" class="form-control" placeholder="usuario@correo.com" value="{{ old('correo_electronico') }}" required autofocus>
                </div>
            </div>

            <button type="submit" class="btn btn-submit btn-primary w-100 text-white">
                <i class="bi bi-send me-2"></i>Enviar Enlace de Recuperación
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" style="color:var(--es-red);font-weight:600;text-decoration:none;font-size:0.85rem;">
                <i class="bi bi-arrow-left me-1"></i>Volver a Iniciar Sesión
            </a>
        </div>

        <hr class="my-3" style="opacity:0.1;">
        <p class="text-center text-muted mb-0" style="font-size:0.72rem;">
            <i class="bi bi-shield-lock me-1"></i>
            Conexión segura · Sistema Digital Consular © {{ date('Y') }}
        </p>
    </div>
</div>
</body>
</html>
