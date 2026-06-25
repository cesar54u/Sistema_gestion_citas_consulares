<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistema de Gestión de Citas - Consulado Honorario del Reino de España en Maracay">
    <title>@yield('title', 'Consulado Honorario España | Maracay')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --es-red: #c60b1e;
            --es-yellow: #ffc400;
            --es-dark: #1a1a2e;
            --es-navy: #16213e;
            --es-blue: #0f3460;
            --sidebar-width: 260px;
            --header-height: 64px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6fb;
            color: #1a1a2e;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: linear-gradient(160deg, var(--es-dark) 0%, var(--es-navy) 50%, var(--es-blue) 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
        }

        .sidebar-brand .brand-logo {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--es-red), #e8051a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            flex-shrink: 0;
            box-shadow: 0 4px 15px rgba(198,11,30,0.4);
        }

        .sidebar-brand .brand-text { color: #fff; }
        .sidebar-brand .brand-text strong { font-size: 0.95rem; font-weight: 700; display: block; }
        .sidebar-brand .brand-text small { font-size: 0.7rem; opacity: 0.7; }

        .sidebar-nav { padding: 1rem 0; flex: 1; overflow-y: auto; }

        .sidebar-section-title {
            padding: 0.5rem 1.25rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4);
            font-weight: 600;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1.25rem;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
            transition: all 0.2s ease;
            margin: 0.1rem 0.75rem;
            border-radius: 8px;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255,255,255,0.12);
            color: #fff;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, var(--es-red), #e8051a);
            color: white;
            box-shadow: 0 4px 12px rgba(198,11,30,0.3);
        }

        .sidebar-link i { font-size: 1rem; min-width: 20px; }

        .sidebar-footer {
            padding: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* ===== TOPBAR ===== */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e8ecf5;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            z-index: 1030;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: calc(var(--header-height) + 1.5rem);
            padding-bottom: 2rem;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
            min-height: 100vh;
            max-width: 100vw;
            overflow-x: hidden;
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: box-shadow 0.2s ease;
        }

        .card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: transform 0.2s ease;
        }

        .stat-card:hover { transform: translateY(-2px); }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-value { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .stat-label { font-size: 0.8rem; color: #6b7280; margin-top: 0.2rem; }

        /* ===== BADGES PERSONALIZADOS ===== */
        .badge-pendiente  { background-color: #fef3c7; color: #92400e; }
        .badge-aprobada   { background-color: #d1fae5; color: #065f46; }
        .badge-rechazada  { background-color: #fee2e2; color: #991b1b; }
        .badge-completada { background-color: #dbeafe; color: #1e40af; }
        .badge-cancelada  { background-color: #f3f4f6; color: #374151; }

        .estado-badge {
            padding: 0.3rem 0.85rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        /* ===== BOTONES ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--es-red), #e8051a);
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #a8091a, var(--es-red));
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(198,11,30,0.3);
        }

        .btn-outline-primary {
            color: var(--es-red);
            border-color: var(--es-red);
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background: var(--es-red);
            border-color: var(--es-red);
        }

        /* ===== FORM ===== */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--es-red);
            box-shadow: 0 0 0 3px rgba(198,11,30,0.12);
        }

        /* ===== TABLE ===== */
        .table { border-radius: 12px; overflow: hidden; }
        .table thead th {
            background: #f8fafd;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            font-weight: 600;
            padding: 0.85rem 1rem;
        }

        .table tbody td { vertical-align: middle; padding: 0.85rem 1rem; }
        .table tbody tr:hover { background-color: #fafbfe; }

        /* ===== ALERTS ===== */
        .alert { border-radius: 12px; border: none; font-size: 0.875rem; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }
        .alert-warning { background: #fef3c7; color: #92400e; }

        /* ===== PAGE TITLE ===== */
        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--es-dark);
            margin-bottom: 0.25rem;
        }

        .page-breadcrumb {
            font-size: 0.8rem;
            color: #9ca3af;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .topbar { left: 0; }
            .main-content { margin-left: 0; padding-left: 1rem; padding-right: 1rem; }
        }

        /* ===== SIDEBAR BACKDROP ===== */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1035;
        }
        .sidebar-backdrop.show { display: block; }

        /* ===== BANDERA ESPAÑOLA ===== */
        .es-flag-stripe {
            height: 4px;
            background: linear-gradient(90deg, var(--es-red) 25%, var(--es-yellow) 25% 75%, var(--es-red) 75%);
        }
    </style>

    @stack('styles')
</head>
<body>
<!-- Franja bandera española -->
<div class="es-flag-stripe"></div>

<!-- SIDEBAR BACKDROP -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <a class="sidebar-brand d-flex align-items-center gap-3" href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}">
        <div class="brand-logo"><i class="bi bi-building-fill"></i></div>
        <div class="brand-text">
            <strong>Consulado España</strong>
            <small>Maracay · Aragua</small>
        </div>
    </a>

    <nav class="sidebar-nav">
        @if(auth()->user()->isAdmin())
            {{-- NAVEGACIÓN ADMIN --}}
            <div class="sidebar-section-title">Principal</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>

            <div class="sidebar-section-title mt-2">Gestión</div>
            <a href="{{ route('admin.usuarios') }}" class="sidebar-link {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Usuarios
            </a>
            <a href="{{ route('admin.servicios') }}" class="sidebar-link {{ request()->routeIs('admin.servicios*') ? 'active' : '' }}">
                <i class="bi bi-briefcase-fill"></i> Servicios
            </a>
            <a href="{{ route('admin.disponibilidad') }}" class="sidebar-link {{ request()->routeIs('admin.disponibilidad*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i> Disponibilidad
            </a>
            <a href="{{ route('admin.citas') }}" class="sidebar-link {{ request()->routeIs('admin.citas*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> Citas
            </a>
            <a href="{{ route('admin.historial') }}" class="sidebar-link {{ request()->routeIs('admin.historial') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Historial
            </a>
        @else
            {{-- NAVEGACIÓN USUARIO --}}
            <div class="sidebar-section-title">Mi Portal</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Inicio
            </a>
            <a href="{{ route('citas.create') }}" class="sidebar-link {{ request()->routeIs('citas.create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle-fill"></i> Agendar Cita
            </a>
            <a href="{{ route('citas.mis-citas') }}" class="sidebar-link {{ request()->routeIs('citas.mis-citas') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i> Mis Citas
            </a>
            <a href="{{ route('citas.historial') }}" class="sidebar-link {{ request()->routeIs('citas.historial') ? 'active' : '' }}">
                <i class="bi bi-clock-history"></i> Historial
            </a>

            <div class="sidebar-section-title mt-2">Mi Cuenta</div>
            <a href="{{ route('perfil') }}" class="sidebar-link {{ request()->routeIs('perfil') ? 'active' : '' }}">
                <i class="bi bi-person-fill"></i> Perfil
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div style="width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill text-white"></i>
            </div>
            <div>
                <div style="color:#fff;font-size:0.8rem;font-weight:600;">{{ Auth::user()->nombre_completo }}</div>
                <div style="color:rgba(255,255,255,0.5);font-size:0.7rem;">{{ Auth::user()->isAdmin() ? 'Administrador' : 'Usuario' }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm w-100" style="background:rgba(255,255,255,0.1);color:rgba(255,255,255,0.8);border-radius:8px;font-size:0.8rem;">
                <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <button class="btn btn-sm d-lg-none" id="sidebarToggle" style="border:1.5px solid #e5e7eb;border-radius:8px;">
        <i class="bi bi-list" style="font-size:1.2rem;"></i>
    </button>

    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-muted" style="font-size:0.8rem;">
            <i class="bi bi-geo-alt-fill me-1" style="color:var(--es-red);"></i>
            Consulado · Maracay, Aragua
        </span>
        <div class="d-flex align-items-center gap-2">
            <div style="width:34px;height:34px;background:linear-gradient(135deg,var(--es-red),#e8051a);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-person-fill text-white" style="font-size:0.9rem;"></i>
            </div>
            <div class="d-none d-md-block">
                <div style="font-size:0.85rem;font-weight:600;">{{ Auth::user()->nombre_completo }}</div>
                <div style="font-size:0.7rem;color:#9ca3af;">{{ Auth::user()->correo_electronico }}</div>
            </div>
        </div>
    </div>
</div>

<!-- MAIN CONTENT -->
<main class="main-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Por favor corrige los errores:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Sidebar toggle móvil
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            if(sidebarBackdrop) sidebarBackdrop.classList.toggle('show');
        });
    }

    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarBackdrop.classList.remove('show');
        });
    }

    // Auto-ocultar alertas
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(a);
            bsAlert.close();
        });
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
