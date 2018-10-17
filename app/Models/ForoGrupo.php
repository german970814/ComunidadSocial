<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

class ForoGrupo extends ModelForm
{
    use CacheMethods;

    protected $table = 'foros_grupo';

    protected $fillable = ['tema', 'usuario_id', 'grupo_investigacion_id'];

    public function grupo() {
        return $this->belongsTo('\App\Models\GrupoInvestigacion', 'grupo_investigacion_id');
    }

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
    }

    public function respuestas() {
        return $this->hasMany('\App\Models\RespuestaForoGrupo', 'foro_id');
    }

    public function get_url() {
        return route('grupos.ver-foro', $this->id);
    }
}