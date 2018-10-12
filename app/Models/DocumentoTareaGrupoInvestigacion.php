<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };


class DocumentoTareaGrupoInvestigacion extends ModelForm
{
    use CacheMethods;

    protected $table = 'documentos_tareas_grupo_investigacion';

    protected $fillable = [
        'archivo', 'tarea_id'
    ];

    public function maestro() {
        return $this->belongsTo('\App\Models\TareaGrupoInvestigacion', 'tarea_id');
    }
}