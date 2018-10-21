<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

class RespuestaForoGrupo extends ModelForm  // TODO: Agregar estado para reportar
{
    use CacheMethods;

    protected $table = 'respuestas_foro_grupo';

    protected $fillable = ['descripcion', 'foro_id', 'usuario_id'];

    public function foro() {
        return $this->belongsTo('\App\Models\ForoGrupo', 'foro_id');
    }

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
    }
}