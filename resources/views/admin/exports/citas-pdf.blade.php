<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Citas — Consulado España Maracay</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size:11px; color:#1a1a2e; background:#fff; }

        .header { background:linear-gradient(135deg,#1a1a2e 0%,#0f3460 100%); color:#fff; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; }
        .header .brand { display:flex; align-items:center; gap:12px; }
        .header .logo  { width:42px; height:42px; background:#c60b1e; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:#fff; font-weight:700; }
        .header h1 { font-size:15px; font-weight:700; margin:0; }
        .header p  { font-size:10px; opacity:0.8; margin:2px 0 0 0; }
        .header .date { font-size:10px; opacity:0.7; text-align:right; }

        .flag { height:5px; background:linear-gradient(90deg,#c60b1e 25%,#ffc400 25% 75%,#c60b1e 75%); }

        .meta { padding:12px 24px; background:#f8fafd; border-bottom:1px solid #e5e7eb; display:flex; gap:24px; align-items:center; flex-wrap:wrap; }
        .meta span { font-size:10px; color:#6b7280; }
        .meta strong { color:#1a1a2e; }

        table { width:100%; border-collapse:collapse; margin-top:12px; }
        thead th { background:#c60b1e; color:#fff; padding:7px 8px; text-align:left; font-size:9px; text-transform:uppercase; letter-spacing:0.5px; font-weight:700; }
        tbody td { padding:6px 8px; border-bottom:1px solid #f3f4f6; font-size:10px; vertical-align:top; }
        tbody tr:nth-child(even) td { background:#f9fafb; }

        .badge { display:inline-block; padding:2px 8px; border-radius:20px; font-size:9px; font-weight:700; }
        .badge-aprobada   { background:#d1fae5; color:#065f46; }
        .badge-rechazada  { background:#fee2e2; color:#991b1b; }
        .badge-pendiente  { background:#fef3c7; color:#92400e; }
        .badge-completada { background:#ede9fe; color:#5b21b6; }
        .badge-cancelada  { background:#f3f4f6; color:#374151; }

        .footer { margin-top:24px; padding:12px 24px; border-top:2px solid #e5e7eb; display:flex; justify-content:space-between; font-size:9px; color:#9ca3af; }

        .btn-bar { position:fixed; top:16px; right:16px; display:flex; gap:8px; z-index:999; }
        .btn-print { background:#c60b1e; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(198,11,30,0.3); }
        .btn-back  { background:#1a1a2e; color:#fff; border:none; padding:10px 16px; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block; }

        @media print {
            .btn-bar { display:none; }
        }
        @page { size: A4 landscape; margin: 10mm; }
    </style>
</head>
<body>

<div class="btn-bar">
    <a class="btn-back" onclick="history.back()">← Volver</a>
    <button class="btn-print" onclick="window.print()">🖨️ Guardar / Imprimir PDF</button>
</div>

<div class="flag"></div>
<div class="header">
    <div class="brand">
        <div class="logo">🏛</div>
        <div>
            <h1>Reporte de Citas Consulares</h1>
            <p>Consulado Honorario del Reino de España · Maracay, Aragua</p>
        </div>
    </div>
    <div class="date">
        Generado el {{ now()->format('d/m/Y') }}<br>
        {{ now()->format('H:i') }} hrs
    </div>
</div>

<div class="meta">
    <span>Total citas: <strong>{{ $citas->count() }}</strong></span>
    @if($usuario)
        <span>Usuario: <strong>{{ $usuario->nombre_completo }} ({{ $usuario->cedula }})</strong></span>
    @else
        <span>Todos los usuarios</span>
    @endif
    @if($request->filled('estado'))
        <span>Estado: <strong>{{ ucfirst($request->estado) }}</strong></span>
    @endif
    @if($request->filled('fecha_desde') || $request->filled('fecha_hasta'))
        <span>Período: <strong>{{ $request->fecha_desde ?? '...' }} — {{ $request->fecha_hasta ?? '...' }}</strong></span>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Solicitada</th>
            <th>Usuario</th>
            <th>Cédula</th>
            <th>Correo</th>
            <th>Servicio</th>
            <th>Precio</th>
            <th>Fecha Cita</th>
            <th>Hora</th>
            <th>Estado</th>
            <th>Gestionada por</th>
            <th>Notas</th>
        </tr>
    </thead>
    <tbody>
        @forelse($citas as $cita)
        <tr>
            <td>{{ $cita->id }}</td>
            <td>{{ $cita->created_at->format('d/m/Y') }}</td>
            <td><strong>{{ $cita->usuario->nombre_completo ?? '-' }}</strong><br><span style="color:#9ca3af;font-size:9px;">{{ $cita->usuario->telefono ?? '' }}</span></td>
            <td>{{ $cita->usuario->cedula ?? '-' }}</td>
            <td style="font-size:9px;">{{ $cita->usuario->correo_electronico ?? '-' }}</td>
            <td>{{ $cita->servicio->nombre_producto ?? '-' }}<br><span style="color:#9ca3af;font-size:9px;">{{ $cita->servicio->tipo ?? '' }}</span></td>
            <td>${{ number_format($cita->servicio->precio ?? 0, 2) }}</td>
            <td>{{ $cita->fecha_cita->format('d/m/Y') }}</td>
            <td>{{ substr($cita->hora_inicio, 0, 5) }}</td>
            <td><span class="badge badge-{{ $cita->estado }}">{{ ucfirst($cita->estado) }}</span></td>
            <td>{{ $cita->admin->nombre_completo ?? '-' }}</td>
            <td style="font-size:9px;">{{ $cita->notas ?? '-' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="12" style="text-align:center;padding:24px;color:#9ca3af;">Sin citas registradas</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>Sistema Digital de Gestión de Citas Consulares</span>
    <span>Impreso el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</span>
</div>

</body>
</html>
