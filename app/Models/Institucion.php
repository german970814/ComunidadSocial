<?php

namespace App\Models;

use App\Libraries\{ ModelForm, CacheMethods };


class Institucion extends ModelForm
{
    use CacheMethods;
    
    protected $table = 'instituciones';

    protected $fillable = [
        'dane', 'nombre', 'codigo', 'director',
        'telefono', 'fax', 'email', 'usuario_id',
        'municipio_id', 'tipo_institucion',
    ];

    static $form_schema = [
        'departamento' => [
            'model' => \App\Models\Departamento::class,
            'param' => 'nombre'
        ],
        'municipio_id' => [
            'label' => 'Municipio',
            'type' => 'select'
        ]
    ];

    public function usuario() {
        return $this->belongsTo('\App\Models\Usuario');
    }

    public function municipio() {
        return $this->belongsTo('\App\Models\Municipio');
    }

    private function _solicitudes() {
        return \App\Models\SolicitudInstitucion::where('institucion_id', $this->id);
    }

    public function solicitudes() {
        return $this->_solicitudes()->where('aceptada', false)->get();
    }

    public function integrantes_ids() {
        $ids = \App\Models\Usuario::join('solicitudes_institucion', 'solicitudes_institucion.usuario_id', 'usuarios.id')
            ->where('solicitudes_institucion.institucion_id', $this->id)
            ->where('aceptada', true)
            ->select('usuarios.id as id')
            ->get();
        return collect($ids->all())->map(function ($result) {
            return $result->id;
        });
    }

    public function estudiantes() {
        return \App\Models\Usuario::find($this->integrantes_ids()->all())
            ->where('tipo_usuario', \App\Models\Usuario::$ESTUDIANTE)
            ->all();
    }

    public function maestros() {
        return \App\Models\Usuario::find($this->integrantes_ids()->all())
            ->where('tipo_usuario', \App\Models\Usuario::$MAESTRO)
            ->all();
    }
}
