<?php

namespace App\Models;

use App\Libraries\ModelForm;

class Departamento extends ModelForm {

    protected $table = 'departamentos';

    protected $fillable = ['nombre', 'codigo'];

    public function municipios() {
        return $this->hasMany('App\Models\Municipio', 'departamento_id');
    }
}