<x-mail::message>
# 🔔 Recordatorio: Tu cita es pronto

Hola **{{ $usuario->nombre_completo }}**,

Te recordamos que tienes una **cita agendada** en el Consulado Honorario del Reino de España en Maracay en aproximadamente **{{ $horasAntes }} hora(s)**.

<x-mail::panel>
**📋 Detalle de tu Cita**

- **Servicio:** {{ $servicio->nombre_producto }}
- **Fecha:** {{ $cita->fecha_cita->format('d/m/Y') }} ({{ $cita->fecha_cita->translatedFormat('l') }})
- **Hora:** {{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}
- **Dirección:** Consulado Honorario de España, Maracay, Aragua
</x-mail::panel>

### 📌 Recomendaciones

- Llega **15 minutos antes** de tu hora de cita.
- Trae tus documentos originales y copias.
- En caso de no poder asistir, cancela tu cita desde el sistema con anticipación.

<x-mail::button :url="config('app.url') . '/citas/mis-citas'" color="red">
Ver mis Citas
</x-mail::button>

---

Saludos,
**Consulado Honorario del Reino de España**
*Maracay, Estado Aragua · Venezuela*
</x-mail::message>
