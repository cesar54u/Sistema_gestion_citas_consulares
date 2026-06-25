<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfiguracionCorreo extends Model
{
    protected $table = 'configuracion_correos';
    
    protected $fillable = [
        'smtp_username',
        'smtp_password',
        'from_address',
        'from_name',
        'cuerpo_recordatorio'
    ];
}
