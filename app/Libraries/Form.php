<?php 

namespace App\Libraries;

use Illuminate\Support\Collection;

class Form
{

    protected $fields = [];

    public function __construct($model, $fields) {
        $this->instance = is_object($model) ? $model : null;
        $this->_model = $this->instance !== null ? get_class($this->instance) : $model;
        $this->fields = $fields;
    }

    public function get_form() {
        return collect($this->fields)->map(function ($field) {
            $attrs = [
                'label' => $this->get_label($field),
                'value' => $this->get_value($field),
            ]
            return [$field => $attrs]
        });
    }

    private get_label($field) {
        if (is_array($field) and isset($field['label'])) {
            return $field['label'];
        }
        return $this->_model->labels[$field];
    }

    private get_value($field) {
        if (is_array($field) and isset($field['value'])) {
            return $field['value'];
        }
        return $this->instance !== null ? $this->instance[$field] : '';
    }
}