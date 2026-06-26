<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\VerificarCorreoNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 'apellido', 'cedula', 'telefono',
        'correo_electronico', 'usuario', 'password', 'rol'
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    // Laravel Auth usa este campo para el email (notificaciones/reset)
    public function getEmailAttribute() { return $this->correo_electronico; }
    public function getAuthPassword() { return $this->password; }

    // Usar nuestra notificación personalizada con diseño del consulado
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerificarCorreoNotification());
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellido}";
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function citas()
    {
        return $this->hasMany(Cita::class, 'usuario_id');
    }

    public function citasGestionadas()
    {
        return $this->hasMany(Cita::class, 'admin_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialSolicitud::class, 'usuario_id');
    }
}
