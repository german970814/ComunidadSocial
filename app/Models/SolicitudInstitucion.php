<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudInstitucion extends Model
{
    protected $table = 'solicitudes_institucion';

    protected $fillable = [
        'usuario_id', 'aceptada', 'institucion_id'
    ];
}