<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods, Helper };


class DocumentoTareaGrupoInvestigacion extends ModelForm
{
    use CacheMethods;

    protected $table = 'documentos_tareas_grupo_investigacion';

    protected $fillable = [
        'archivo', 'tarea_id'
    ];

    public function tarea() {
        return $this->belongsTo('\App\Models\TareaGrupoInvestigacion', 'tarea_id');
    }

    public function get_nombre() {
        $extension = $this->get_extension();
        $from = '/'.preg_quote('.' . $extension, '/') . '/';
        $name_without_ext = preg_replace($from, '', $this->archivo, 1);
        return str_replace('tareas/' . $this->tarea_id . '/', '', $name_without_ext);
    }

    public function get_extension() {
        $extension = explode('.', $this->archivo);
        return end($extension);
    }

    public function get_url() {
        return route('aula.ver-documento', $this->id);
    }

    public function get_eliminar_url() {
        return route('aula.eliminar-documento', $this->id);
    }

    public function get_icon() {
        return Helper::get_file_icon($this->get_extension());
    }
}