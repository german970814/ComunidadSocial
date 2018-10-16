<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods, Helper };


class ExamenGrupoInvestigacion extends ModelForm
{
    use CacheMethods;

    protected $table = 'examen_grupo_investigacion';

    protected $fillable = [
        'titulo', 'preguntas', 'duracion',
        'fecha_fin', 'fecha_inicio', 'descripcion',
        'maestro_id', 'grupo_investigacion_id'
    ];

    static $form_schema = [
        'descripcion' => [
            'type' => 'textarea',
            'label' => 'Descripción'
        ],
        'preguntas' => [
            'type' => 'hidden'
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
        ],
        'duracion' => [
            'type' => 'number',
            'label' => 'Duración (min)'
        ]
    ];

    public function maestro() {
        return $this->belongsTo('\App\Models\Usuario', 'maestro_id');
    }

    public function grupo() {
        return $this->belongsTo('\App\Models\GrupoInvestigacion', 'grupo_investigacion_id');
    }

    public function entregas() {
        return $this->hasMany('\App\Models\EntregaExamenEstudiante', 'examen_id');
    }

    public function is_activo() {
        $now = new \DateTime();
        return $now >= (new \DateTime($this->fecha_inicio)) && $now <= (new \DateTime($this->fecha_fin));
    }

    public function get_titulo() {
        return $this->titulo;
    }

    public function get_url() {
        return route('aula.ver-examen', $this->id);
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