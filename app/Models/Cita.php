<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $fillable = [
        'usuario_id', 'servicio_id', 'admin_id', 'fecha_cita',
        'hora_inicio', 'estado', 'notas', 'motivo_rechazo', 'fecha_modificacion'
    ];

    protected $casts = ['fecha_cita' => 'date', 'fecha_modificacion' => 'datetime'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialSolicitud::class, 'cita_id');
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'pendiente'  => 'warning',
            'aprobada'   => 'success',
            'rechazada'  => 'danger',
            'completada' => 'info',
            'cancelada'  => 'secondary',
            default      => 'light',
        };
    }
}
