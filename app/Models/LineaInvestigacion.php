<?php

namespace App\Models;

use App\Libraries\ModelForm;

class LineaInvestigacion extends ModelForm
{
    protected $table = 'lineas_investigacion';

    protected $fillable = ['nombre', 'codigo', 'tipo', 'descripcion'];

    static $TEMATICA = 'T';
    static $INVESTIGACION = 'I';

    static $tipo_opciones = [
        'T' => 'Temática',
        'I' => 'Investigación'
    ];
}