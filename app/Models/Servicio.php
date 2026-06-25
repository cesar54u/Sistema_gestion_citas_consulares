<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = ['nombre_producto', 'tipo', 'duracion', 'precio', 'descripcion', 'estado'];

    protected $casts = ['estado' => 'boolean', 'precio' => 'decimal:2'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'servicio_id');
    }

    public function historial()
    {
        return $this->hasMany(HistorialSolicitud::class);
    }

    public function disponibilidad()
    {
        return $this->hasMany(Disponibilidad::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }
}
