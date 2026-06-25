<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Solicitudes — Consulado España Maracay</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a2e; background: #fff; }

        .header { background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%); color: white; padding: 18px 24px; display:flex; align-items:center; justify-content:space-between; }
        .header .brand { display:flex; align-items:center; gap:12px; }
        .header .logo { width:42px; height:42px; background:#c60b1e; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.2rem; color:#fff; font-weight:700; }
        .header h1 { font-size:15px; font-weight:700; margin:0; }
        .header p  { font-size:10px; opacity:0.8; margin:2px 0 0 0; }
        .header .date { font-size:10px; opacity:0.7; text-align:right; }

        .flag { height:5px; background:linear-gradient(90deg,#c60b1e 25%,#ffc400 25% 75%,#c60b1e 75%); }

        .meta { padding: 12px 24px; background: #f8fafd; border-bottom: 1px solid #e5e7eb; display:flex; gap:24px; align-items:center; }
        .meta span { font-size:10px; color:#6b7280; }
        .meta strong { color:#1a1a2e; }

        table { width:100%; border-collapse:collapse; margin-top:12px; }
        thead th { background:#c60b1e; color:#fff; padding:7px 8px; text-align:left; font-size:9.5px; text-transform:uppercase; letter-spacing:0.5px; font-weight:700; }
        tbody td { padding:6px 8px; border-bottom:1px solid #f3f4f6; font-size:10px; vertical-align:top; }
        tbody tr:nth-child(even) td { background:#f9fafb; }
        tbody tr:hover td { background:#fef3c7; }

        .badge { display:inline-block; padding:2px 8px; border-radius:20px; font-size:9px; font-weight:700; }
        .badge-aprobada   { background:#d1fae5; color:#065f46; }
        .badge-rechazada  { background:#fee2e2; color:#991b1b; }
        .badge-pendiente  { background:#fef3c7; color:#92400e; }
        .badge-completada { background:#ede9fe; color:#5b21b6; }
        .badge-cancelada  { background:#f3f4f6; color:#374151; }

        .accion-aprobacion     { background:#d1fae5; color:#065f46; }
        .accion-rechazo        { background:#fee2e2; color:#991b1b; }
        .accion-creacion       { background:#dbeafe; color:#1e40af; }
        .accion-reprogramacion { background:#fef3c7; color:#92400e; }
        .accion-completada     { background:#ede9fe; color:#5b21b6; }

        .footer { margin-top:24px; padding:12px 24px; border-top:2px solid #e5e7eb; display:flex; justify-content:space-between; font-size:9px; color:#9ca3af; }

        .no-print-msg { display:none; }

        .btn-bar { position:fixed; top:16px; right:16px; display:flex; gap:8px; z-index:999; }
        .btn-print { background:#c60b1e; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-size:13px; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(198,11,30,0.3); }
        .btn-back  { background:#1a1a2e; color:#fff; border:none; padding:10px 16px; border-radius:8px; font-size:13px; cursor:pointer; text-decoration:none; display:inline-block; }

        @media print {
            .btn-bar { display:none; }
            body { margin:0; }
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
            <h1>Historial de Solicitudes</h1>
            <p>Consulado Honorario del Reino de España · Maracay, Aragua</p>
        </div>
    </div>
    <div class="date">
        Generado el {{ now()->format('d/m/Y') }}<br>
        {{ now()->format('H:i') }} hrs
    </div>
</div>

<div class="meta">
    <span>Total registros: <strong>{{ $historial->count() }}</strong></span>
    @if($usuario)
        <span>Usuario: <strong>{{ $usuario->nombre_completo }} ({{ $usuario->cedula }})</strong></span>
    @else
        <span>Todos los usuarios</span>
    @endif
    @if($request->filled('accion'))
        <span>Acción: <strong>{{ ucfirst($request->accion) }}</strong></span>
    @endif
    @if($request->filled('fecha_desde') || $request->filled('fecha_hasta'))
        <span>Período: <strong>{{ $request->fecha_desde ?? '...' }} — {{ $request->fecha_hasta ?? '...' }}</strong></span>
    @endif
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Fecha Mod.</th>
            <th>Usuario</th>
            <th>Cédula</th>
            <th>Servicio</th>
            <th>Fecha Cita</th>
            <th>Hora</th>
            <th>Acción</th>
            <th>Estado</th>
            <th>Admin</th>
            <th>Descripción</th>
        </tr>
    </thead>
    <tbody>
        @forelse($historial as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->fecha_modificacion?->format('d/m/Y H:i') ?? '-' }}</td>
            <td><strong>{{ $item->usuario->nombre_completo ?? '-' }}</strong></td>
            <td>{{ $item->usuario->cedula ?? '-' }}</td>
            <td>{{ $item->servicio->nombre_producto ?? '-' }}</td>
            <td>{{ $item->fecha_cita?->format('d/m/Y') ?? '-' }}</td>
            <td>{{ $item->hora_inicio ? substr($item->hora_inicio,0,5) : '-' }}</td>
            <td><span class="badge accion-{{ $item->accion }}">{{ ucfirst($item->accion ?? '-') }}</span></td>
            <td><span class="badge badge-{{ $item->estado }}">{{ ucfirst($item->estado ?? '-') }}</span></td>
            <td>{{ $item->admin->nombre_completo ?? '-' }}</td>
            <td>{{ $item->descripcion }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="11" style="text-align:center; padding:24px; color:#9ca3af;">Sin registros de historial</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>Sistema Digital de Gestión de Citas Consulares</span>
    <span>Página impresa el {{ now()->format('d/m/Y \a \l\a\s H:i') }}</span>
</div>

</body>
</html>
