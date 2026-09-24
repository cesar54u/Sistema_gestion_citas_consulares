<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | Consulado España Maracay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --es-red: #c60b1e; --es-yellow: #ffc400; }
        body { font-family: 'Inter', sans-serif; background: #f0f2f8; min-height: 100vh; display: flex; flex-direction: column; }
        .es-flag { height: 5px; background: linear-gradient(90deg, var(--es-red) 25%, var(--es-yellow) 25% 75%, var(--es-red) 75%); }
        .welcome-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .welcome-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 800px;
            padding: 3rem;
            text-align: center;
            position: relative;
        }
        .brand-icon { 
            width: 80px; 
            height: 80px; 
            background: linear-gradient(135deg, var(--es-red), #e8051a); 
            border-radius: 20px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 2.5rem; 
            color: white; 
            box-shadow: 0 8px 25px rgba(198,11,30,0.4); 
            margin: 0 auto 1.5rem auto;
        }
        .btn-custom { 
            background: linear-gradient(135deg, var(--es-red), #e8051a); 
            border: none; 
            border-radius: 12px; 
            padding: 0.8rem 1.5rem; 
            font-weight: 600; 
            font-size: 1rem; 
            color: white;
            transition: all 0.2s; 
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-custom:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 8px 20px rgba(198,11,30,0.35); 
            color: white;
        }
        .btn-outline-custom {
            background: transparent;
            border: 2px solid var(--es-red);
            color: var(--es-red);
            border-radius: 12px; 
            padding: 0.75rem 1.5rem; 
            font-weight: 600; 
            font-size: 1rem; 
            transition: all 0.2s; 
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-outline-custom:hover {
            background: rgba(198,11,30,0.05);
            color: var(--es-red);
        }
        .info-box {
            background: #f8fafd;
            border: 1.5px dashed #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        @media (max-width: 575.98px) {
            .welcome-card { padding: 2rem 1.5rem; }
            .btn-custom, .btn-outline-custom { width: 100%; }
        }
    </style>
</head>
<body>
<div class="es-flag"></div>
<div class="welcome-wrapper">
    <div class="welcome-card">
        <div class="brand-icon"><i class="bi bi-building-fill"></i></div>
        <h1 class="fw-700 mb-2" style="font-size: 2.2rem; color: #1a1a2e;">Sistema de Gestión de Citas</h1>
        <h2 class="fw-500 mb-4" style="font-size: 1.2rem; color: #6b7280;">Consulado Honorario del Reino de España en Maracay</h2>
        
        <p class="text-muted mb-4">
            Bienvenido al portal digital del Consulado Honorario. Desde aquí podrá gestionar sus citas para trámites consulares de forma rápida, simple y segura.
        </p>

        <!-- CUADRO DE TEXTO PARA EDICIÓN DEL USUARIO -->
        <div class="info-box">
            <h5 class="fw-600 mb-2" style="color: #0f3460;"><i class="bi bi-info-circle-fill me-2" style="color: var(--es-yellow);"></i>Información Importante</h5>
            <p class="mb-0 text-muted" style="font-size: 0.95rem;">
                <!-- EDITAR AQUÍ: Pon aquí el pequeño texto adicional para aclararle un par de cosas al usuario -->
                [ESPACIO RESERVADO: El administrador debe editar este texto para añadir aclaraciones adicionales sobre los trámites consulares]
            </p>
        </div>
        <!-- FIN CUADRO DE TEXTO -->

        <div class="d-flex gap-3 justify-content-center mt-2 flex-wrap">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-custom">
                    <i class="bi bi-grid-fill"></i> Ir al Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-custom">
                    <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-outline-custom">
                        <i class="bi bi-person-plus-fill"></i> Registrarse
                    </a>
                @endif
            @endauth
        </div>
        
        <div class="mt-5 text-muted" style="font-size:0.75rem;">
            <hr style="opacity: 0.1; margin-bottom: 1rem;">
            <i class="bi bi-shield-lock me-1"></i> Conexión segura · Sistema Digital Consular © {{ date('Y') }}
        </div>
    </div>
</div>
</body>
</html>
