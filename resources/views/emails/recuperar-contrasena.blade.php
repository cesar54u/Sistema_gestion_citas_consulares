<x-mail::message>
<div style="text-align:center; margin-bottom: 24px;">
<img src="{{ asset('favicon.ico') }}" width="40" alt="Consulado">
</div>

# Recuperación de Contraseña

Hola, **{{ $user->nombre }}**.

Hemos recibido una solicitud para restablecer la contraseña de tu cuenta en el **Sistema Digital de Gestión de Citas del Consulado Honorario del Reino de España en Maracay**.

Haz clic en el siguiente botón para establecer una nueva contraseña:

<x-mail::button :url="$resetUrl" color="red">
🔑 Restablecer mi Contraseña
</x-mail::button>

> ⚠️ **Este enlace expirará en 60 minutos.** Si no lo usas a tiempo, deberás solicitar uno nuevo.

---

**¿No solicitaste este cambio?**
Si no realizaste esta solicitud, puedes ignorar este correo de forma segura. Tu contraseña actual seguirá siendo la misma.

---

Con gusto te asistimos,
**Consulado Honorario del Reino de España**
*Maracay, Estado Aragua · Venezuela*

<x-mail::subcopy>
Si tienes problemas con el botón, copia y pega este enlace en tu navegador: {{ $resetUrl }}
</x-mail::subcopy>
</x-mail::message>
