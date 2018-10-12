<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };


class TareaGrupoInvestigacion extends ModelForm
{
    use CacheMethods;

    protected $table = 'tareas_grupo_investigacion';

    protected $fillable = [
        'titulo', 'fecha_fin', 'fecha_inicio',
        'descripcion', 'maestro_id', 'grupo_investigacion_id'
    ];

    static $form_schema = [
        'descripcion' => [
            'type' => 'textarea',
            'label' => 'Descripción'
        ],
        'fecha_fin' => [
            'type' => 'date',
            'label' => 'Fecha fin'
        ],
        'fecha_inicio' => [
            'type' => 'date',
            'label' => 'Fecha inicio'
        ],
        'titulo' => [
            'label' => 'Título'
        ]
    ];

    public function maestro() {
        return $this->belongsTo('\App\Models\Usuario', 'maestro_id');
    }

    public function grupo() {
        return $this->belongsTo('\App\Models\GrupoInvestigacion', 'grupo_investigacion_id');
    }

    public function entregas() {
        return $this->hasMany('\App\Models\EntregaTareaEstudiante', 'tarea_id');
    }

    public function get_titulo() {
        return $this->titulo;
    }

    public function get_url() {
        return route('aula.ver-tarea', $this->id);
    }

    public function get_tiempo_restante() {
        $now = new \DateTime('NOW');
        $fecha_fin = new \DateTime($this->fecha_fin);

        $diferencia = $now->diff($fecha_fin);
        if ($diferencia->d) {
            $day = $diferencia->d == 1 ? ' día' : ' días';
            return 'Quedan ' . $diferencia->d . $day . ' con ' . $diferencia->h . ' horas y ' . $diferencia->i . ' minutos';
        }
        return 'Quedan ' . $diferencia->h . ' horas y ' . $diferencia->i . ' minutos';
    }
}