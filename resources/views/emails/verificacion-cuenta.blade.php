<x-mail::message>
<div style="text-align:center; margin-bottom: 24px;">
<img src="{{ asset('favicon.ico') }}" width="40" alt="Consulado">
</div>

# ¡Bienvenido/a, {{ $user->nombre }}! 🎉

Gracias por registrarte en el **Sistema Digital de Gestión de Citas del Consulado Honorario del Reino de España en Maracay**.

Para activar tu cuenta y poder agendar tus citas, necesitamos verificar tu dirección de correo electrónico. Solo haz clic en el botón a continuación:

<x-mail::button :url="$verificationUrl" color="red">
✅ Verificar mi Correo Electrónico
</x-mail::button>

> ⚠️ **Este enlace expirará en 60 minutos.** Si no lo usas a tiempo, podrás solicitar uno nuevo desde el sistema.

---

**¿No creaste esta cuenta?**
Si no solicitaste este registro, puedes ignorar este correo de forma segura. Nadie tendrá acceso a tu cuenta sin verificar este enlace.

---

Con gusto te asistimos,
**Consulado Honorario del Reino de España**
*Maracay, Estado Aragua · Venezuela*

<x-mail::subcopy>
Si tienes problemas con el botón, copia y pega este enlace en tu navegador: {{ $verificationUrl }}
</x-mail::subcopy>
</x-mail::message>
