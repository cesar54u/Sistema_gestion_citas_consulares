<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FechaBloqueada extends Model
{
    protected $table = 'fechas_bloqueadas';
    protected $fillable = ['fecha', 'motivo'];
}
