<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudAmistad extends Model
{
    /**
     * Nombre de la tabla
     */
    protected $table = 'solicitudes';

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario');
    }
}
