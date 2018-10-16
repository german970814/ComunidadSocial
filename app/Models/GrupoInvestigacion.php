<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };

/**
 * Un grupo de investigación se crea con una linea de investigacion
 * del tipo de investigación, la cual funciona solo como categorización.
 * 
 * Una red temática se crea con la linea de investigación del tipo 
 * temática, la cual será la base para crear el grupo
 */
class GrupoInvestigacion extends ModelForm
{
    use CacheMethods;

    protected $table = 'grupos_investigacion';

    protected $fillable = [
        'nombre', 'tipo', 'photo',
        'descripcion', 'institucion_id', 'linea_investigacion_id'
    ];

    static $TEMATICA = 'T';
    static $INVESTIGACION = 'I';

    static $tipo_opciones = [
        'T' => 'Temática',
        'I' => 'Investigativa'
    ];

    public function get_nombre() {
        if ($this->tipo == self::$TEMATICA) {
            $grupos = GrupoInvestigacion::where('institucion_id', $this->institucion_id)
            ->where('linea_investigacion_id', $this->linea_investigacion_id)
            ->where('tipo', self::$TEMATICA)
            ->get()->all();
            $counter = 0;

            if (count($grupos) - 1) {
                foreach ($grupos as $key => $grupo) {
                    if ($grupo->id == $this->id) {
                        $counter = $key;
                        break;
                    }
                }
            } else {
                return $this->linea_investigacion->nombre;
            }
            return $this->linea_investigacion->nombre . ' (' . ($counter + 1) . ')';
        }
        return $this->nombre;
    }

    public function institucion() {
        return $this->belongsTo('\App\Models\Institucion');
    }

    public function linea_investigacion() {
        return $this->belongsTo('\App\Models\LineaInvestigacion');
    }

    private function solicitudes_ingreso() {
        return $this->hasMany('\App\Models\SolicitudGrupoInvestigacion', 'grupo_investigacion_id');
    }

    public function get_solicitudes_ingreso() {
        return $this->solicitudes_ingreso()
            ->where('aceptada', false)
            ->get()
            ->all();
    }

    /**
     * Posts que le han publicado al grupo
     */
    public function posts() {
        return $this->hasMany('\App\Models\Post', 'grupo_destino_id')
            ->where('posts.estado', \App\Models\Post::$ACTIVO);
    }

    public function feed() {
        return $this->posts()->orderBy('created_at', 'desc');
    }

    public function tareas() {
        return $this->hasMany('\App\Models\TareaGrupoInvestigacion', 'grupo_investigacion_id')
            ->orderBy('fecha_inicio', 'asc');
    }

    public function tareas_activas() {
        $now = new \DateTime();
        return $this->tareas()->where('fecha_inicio', '<=', $now)
            ->where('fecha_fin', '>=', $now);
    }

    public function examenes() {
        return $this->hasMany('\App\Models\ExamenGrupoInvestigacion', 'grupo_investigacion_id')
            ->orderBy('fecha_inicio', 'asc');
    }

    public function examenes_activos() {
        $now = new \DateTime();
        return $this->examenes()->where('fecha_inicio', '<=', $now)
            ->where('fecha_fin', '>=', $now);
    }

    public function maestros() {
        $integrantes = $this->solicitudes_ingreso()
            ->where('aceptada', true)
            ->select('usuario_id as id')
            ->get();
        return \App\Models\Usuario::find($integrantes->map(function ($obj) {
            return $obj->id;
        }))->where('tipo_usuario', \App\Models\Usuario::$MAESTRO)->all();
    }

    public function estudiantes() {
        $integrantes = $this->solicitudes_ingreso()
            ->where('aceptada', true)
            ->select('usuario_id as id')
            ->get();
        return \App\Models\Usuario::find($integrantes->map(function ($obj) {
            return $obj->id;
        }))->where('tipo_usuario', \App\Models\Usuario::$ESTUDIANTE)->all();
    }

    public function get_imagen_url() {
        return asset('assets/img/group-none.png');
    }
}