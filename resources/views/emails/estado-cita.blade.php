<x-mail::message>
@php
$emojis = ['aprobada'=>'✅','rechazada'=>'❌','completada'=>'🎉','cancelada'=>'🚫'];
$emoji = $emojis[$cita->estado] ?? '📋';
@endphp

# {{ $emoji }} Tu cita ha sido {{ ucfirst($cita->estado) }}

Hola **{{ $usuario->nombre_completo }}**,

Te informamos que el estado de tu cita en el Consulado Honorario del Reino de España en Maracay ha sido actualizado.

<x-mail::panel>
**📋 Información de tu Cita**

- **Servicio:** {{ $servicio->nombre_producto }}
- **Fecha:** {{ $cita->fecha_cita->format('d/m/Y') }}
- **Hora:** {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
- **Estado Actual:** {{ strtoupper($cita->estado) }}
@if($cita->estado === 'rechazada' && $cita->motivo_rechazo)
- **Motivo:** {{ $cita->motivo_rechazo }}
@endif
</x-mail::panel>

@if($cita->estado === 'aprobada')
✅ **Tu cita ha sido confirmada.** Te esperamos en la fecha y hora indicada. Recuerda llegar 15 minutos antes y traer tus documentos.
@elseif($cita->estado === 'rechazada')
❌ **Lamentablemente tu cita fue rechazada.** Puedes agendar una nueva cita desde el sistema cuando lo desees.
@elseif($cita->estado === 'completada')
🎉 **¡Gracias por tu visita!** Esperamos haberte atendido satisfactoriamente.
@endif

<x-mail::button :url="config('app.url') . '/citas/mis-citas'" color="red">
Ver mis Citas
</x-mail::button>

---

Saludos,
**Consulado Honorario del Reino de España**
*Maracay, Estado Aragua · Venezuela*
</x-mail::message>
