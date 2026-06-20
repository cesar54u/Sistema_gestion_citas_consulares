<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    protected $table = 'correos';

    protected $fillable = ['emisor', 'destinatario', 'asunto', 'cuerpo', 'estado', 'intentos'];
}
