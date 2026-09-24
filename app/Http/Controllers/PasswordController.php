<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordController extends Controller
{
    // =============================================
    // CAMBIO DE CONTRASEÑA (usuarios autenticados)
    // =============================================

    public function showChangeForm()
    {
        return view('usuario.cambiar-contrasena');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'password.required'         => 'La nueva contraseña es obligatoria.',
            'password.min'              => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'        => 'Las contraseñas no coinciden.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }

    // =============================================
    // RECUPERACIÓN DE CONTRASEÑA (usuarios no autenticados)
    // =============================================

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
        ], [
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.email'    => 'Ingrese un correo electrónico válido.',
        ]);

        // El broker de Laravel busca por 'email', pero nuestro campo es 'correo_electronico'
        // Necesitamos buscar al usuario manualmente y enviar la notificación
        $user = User::where('correo_electronico', $request->correo_electronico)->first();

        if ($user) {
            $token = Password::broker()->createToken($user);
            $user->sendPasswordResetNotification($token);
        }

        // Siempre mostramos el mismo mensaje para no revelar si el correo existe o no
        return back()->with('status', 'Si el correo está registrado, recibirás un enlace de recuperación en tu bandeja de entrada.');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'              => 'required',
            'correo_electronico' => 'required|email',
            'password'           => 'required|string|min:8|confirmed',
        ], [
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.email'    => 'Ingrese un correo electrónico válido.',
            'password.required'           => 'La nueva contraseña es obligatoria.',
            'password.min'                => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'          => 'Las contraseñas no coinciden.',
        ]);

        // Buscar al usuario manualmente
        $user = User::where('correo_electronico', $request->correo_electronico)->first();

        if (!$user) {
            return back()->withErrors(['correo_electronico' => 'No encontramos un usuario con ese correo electrónico.']);
        }

        // Verificar el token
        if (!Password::broker()->tokenExists($user, $request->token)) {
            return back()->withErrors(['correo_electronico' => 'El enlace de recuperación ha expirado o es inválido. Solicita uno nuevo.']);
        }

        // Resetear la contraseña
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Eliminar el token usado
        Password::broker()->deleteToken($user);

        return redirect()->route('login')->with('success', '¡Contraseña restablecida correctamente! Ya puedes iniciar sesión.');
    }
}
