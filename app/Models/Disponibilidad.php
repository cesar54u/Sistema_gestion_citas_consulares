<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    protected $table = 'disponibilidad';

    protected $fillable = ['servicio_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'max_citas', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public static function diasOrdenados(): array
    {
        return ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
    }
}
