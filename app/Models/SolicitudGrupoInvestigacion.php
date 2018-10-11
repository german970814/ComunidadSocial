<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

class SolicitudGrupoInvestigacion extends ModelForm
{
    protected $table = 'solicitudes_grupo_investigacion';

    protected $fillable = [
        'usuario_id', 'aceptada', 'grupo_investigacion_id'
    ];

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario');
    }

    public function grupo_investigacion() {
        return $this->belongsTo('\App\Models\GrupoInvestigacion', 'grupo_investigacion_id');
    }
}