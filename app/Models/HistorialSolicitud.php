<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistorialSolicitud extends Model
{
    protected $table = 'historial_solicitudes';

    protected $fillable = [
        'cita_id', 'usuario_id', 'servicio_id', 'admin_id',
        'fecha_cita', 'hora_inicio', 'estado', 'accion', 'descripcion', 'fecha_modificacion'
    ];

    protected $casts = ['fecha_cita' => 'date', 'fecha_modificacion' => 'datetime'];

    public function cita()
    {
        return $this->belongsTo(Cita::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
