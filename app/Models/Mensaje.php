<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

class Mensaje extends ModelForm
{
    use CacheMethods;

    protected $table = 'messages';

    protected $fillable = [
        'usuario_id', 'conversacion_id', 'mensaje'
    ];

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
    }

    public function conversacion() {
        return $this->belongsTo('\App\Models\Conversacion', 'conversacion_id');
    }
}