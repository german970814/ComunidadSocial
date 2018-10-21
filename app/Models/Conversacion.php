<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

class Conversacion extends ModelForm
{
    use CacheMethods;

    protected $table = 'conversaciones';

    protected $fillable = [
        'emisor_id', 'receptor_id'
    ];

    public function emisor() {
        return $this->belongsTo('\App\Models\Usuario', 'emisor_id');
    }

    public function receptor() {
        return $this->belongsTo('\App\Models\Usuario', 'receptor_id');
    }

    public function mensajes() {
        return $this
            ->hasMany('\App\Models\Mensaje', 'conversacion_id')
            ->orderBy('created_at', 'desc');
    }
}