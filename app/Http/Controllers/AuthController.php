<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo_electronico' => 'required|email',
            'password'           => 'required|string',
        ], [
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.email'    => 'Ingrese un correo electrónico válido.',
            'password.required'           => 'La contraseña es obligatoria.',
        ]);

        $user = User::where('correo_electronico', $request->correo_electronico)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['correo_electronico' => 'Credenciales incorrectas.'])->withInput();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:100',
            'apellido'           => 'required|string|max:100',
            'cedula'             => 'required|string|max:20|unique:usuarios,cedula',
            'telefono'           => 'nullable|string|max:20',
            'correo_electronico' => 'required|email|max:150|unique:usuarios,correo_electronico',
            'usuario'            => 'required|string|max:50|unique:usuarios,usuario',
            'password'           => 'required|string|min:8|confirmed',
        ], [
            'nombre.required'             => 'El nombre es obligatorio.',
            'apellido.required'           => 'El apellido es obligatorio.',
            'cedula.required'             => 'La cédula es obligatoria.',
            'cedula.unique'               => 'Esta cédula ya está registrada.',
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.unique'   => 'Este correo ya está registrado.',
            'usuario.required'            => 'El nombre de usuario es obligatorio.',
            'usuario.unique'              => 'Este nombre de usuario ya está en uso.',
            'password.required'           => 'La contraseña es obligatoria.',
            'password.min'                => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'          => 'Las contraseñas no coinciden.',
        ]);

        $user = User::create([
            'nombre'             => $request->nombre,
            'apellido'           => $request->apellido,
            'cedula'             => $request->cedula,
            'telefono'           => $request->telefono,
            'correo_electronico' => $request->correo_electronico,
            'usuario'            => $request->usuario,
            'password'           => Hash::make($request->password),
            'rol'                => 'usuario',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
    }

    public function showPerfil()
    {
        return view('usuario.perfil', ['user' => Auth::user()]);
    }

    public function updatePerfil(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombre'             => 'required|string|max:100',
            'apellido'           => 'required|string|max:100',
            'telefono'           => 'nullable|string|max:20',
            'correo_electronico' => 'required|email|max:150|unique:usuarios,correo_electronico,' . $user->id,
        ]);

        $user->update($request->only('nombre', 'apellido', 'telefono', 'correo_electronico'));

        return back()->with('success', 'Perfil actualizado correctamente.');
    }

    private function redirectByRole(User $user)
    {
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }
}
