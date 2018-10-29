<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

class EntregaExamenEstudiante extends ModelForm
{
    use CacheMethods;

    protected $table = 'entregas_examen_estudiante';

    protected $fillable = [
        'respuestas', 'examen_id', 
        'usuario_id', 'cerrada',
    ];

    public function examen() {
        return $this->belongsTo('\App\Models\ExamenGrupoInvestigacion', 'examen_id');
    }

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario', 'usuario_id');
    }

    public function get_fecha_fin($str=true) {
        $fecha_inicio = new \DateTime($this->created_at);
        $fecha_inicio->add(new \DateInterval("PT" . $this->examen->duracion . "M"));
        if ($str) {
            return $fecha_inicio->format('Y-m-d H:i:s');
        }
        return $fecha_inicio;
    }

    public function is_editable() {
        // $a->add(new DateInterval("PT{hours}H")) // for hours
        // $a->add(new DateInterval("PT{minutes}M")) // for minutes
        $now = new \DateTime();
        $created = $this->get_fecha_fin(false);
        return $this->examen->is_activo() && $now <= $created && !$this->cerrada;
    }

    public function get_respuesta_pregunta($pregunta_id) {
        $respuestas = json_decode($this->respuestas);
        // $preguntas = json_decode($this->examen->preguntas);

        foreach ($respuestas as $respuesta) {
            if ($respuesta->pregunta == $pregunta_id) {
                return $respuesta;
            }
        }
        return null;
    }

    public function get_calificacion() {
        return ((float)$this->get_respuestas_correctas()) / ((float) json_decode($this->examen->preguntas));
    }

    public function get_respuestas_correctas() {
        $preguntas = json_decode($this->examen->preguntas);
        $data_respuestas = [];

        foreach ($preguntas as $pregunta) {
            $filter = array_filter($pregunta->opciones, function($opcion) {
                return $opcion->respuesta;
            });
            $data_respuestas[$pregunta->id] = array_map(function($option) {
                return $option->id;
            }, $filter);
        }

        $respuestas = json_decode($this->respuestas);
        $respuestas_correctas = [];

        foreach ($respuestas as $respuesta) {
            foreach ($respuesta->respuestas as $rp) {
                if (in_array($rp, $data_respuestas[$respuesta->pregunta])) {
                    $respuestas_correctas[] = $respuesta;
                    break;
                }
            }
        }

        // $this->numero_respuestas_correctas = count($respuestas_correctas);
        // $this->respuestas_correctas = $respuestas_correctas;
        return $respuestas_correctas;
    }
}
