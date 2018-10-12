<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };


class EntregaTareaEstudiante extends ModelForm
{
    use CacheMethods;

    protected $table = 'entregas_tarea_estudiante';

    protected $fillable = [
        'archivo', 'descripcion', 'tarea_id',
        'usuario_id', 'calificacion'
    ];

    public function usuario() {
        return $this->belongsTo('App\Models\Usuario');
    }

    public function tarea() {
        return $this->belongsTo('App\Models\TareaGrupoInvestigacion', 'tarea_id');
    }

    public function is_editable() {
        $now = new \DateTime('NOW');
        $fecha_fin = new \DateTime($this->fecha_fin);
        return $now < $fecha_fin;
    }
}