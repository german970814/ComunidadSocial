<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods, Helper };


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
        $fecha_fin = new \DateTime($this->tarea->fecha_fin);
        return $now < $fecha_fin;
    }

    public function get_extension() {
        $extension = explode('.', $this->archivo);
        return end($extension);
    }

    public function get_documento_url() {
        return route('aula.ver-documento-entrega', $this->id);
    }

    public function get_icon() {
        if ($this->archivo) {
            return Helper::get_file_icon($this->get_extension());
        }
        return '';
    }
}