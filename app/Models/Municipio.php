<?php

namespace App\Models;

use App\Libraries\ModelForm;

class Municipio extends ModelForm {

    protected $table = 'municipios';

    protected $fillable = ['nombre', 'codigo', 'departamento_id'];

    public function departamento() {
        return $this->belongsTo('App\Models\Departamento');
    }
}