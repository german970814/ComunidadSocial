<?php

namespace App\Libraries;

use Illuminate\Database\Eloquent\Model;

class ModelForm extends Model
{
    public static function get_fillable() {
        $instance_class = static::class;
        $instance = new $instance_class();
        return $instance->fillable;
    }
}