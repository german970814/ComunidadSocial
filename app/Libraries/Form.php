<?php 

namespace App\Libraries;

use Illuminate\Support\Collection;

class Form
{

    protected $fields = [];

    public function __construct($model, $fields) {
        $this->instance = is_object($model) ? $model : null;
        $this->_model = $this->instance !== null ? get_class($this->instance) : $model;
        $this->_fields = $fields;
        $this->fill_fields();
    }

    private function fill_fields() {
        foreach ($this->_fields as $field) {
            $default_config_for_field = [
                'type' => 'text',
                'label' => $this->get_label($field),
                'value' => $this->get_value($field),
                'xs' => '12', 'sm' => '12'
            ];
            $config = isset($this->_model::$form_schema[$field]) ?
                $this->_model::$form_schema[$field] : [];
            $final_config = array_merge($default_config_for_field, $config);
            $final_config['xs'] = $this->get_css_class('xs', $final_config['xs']);
            $final_config['sm'] = $this->get_css_class('sm', $final_config['sm']);
            $this->fields[$field] = $final_config;
        }
    }

    public function get_form() {
        return $this->fields;
    }

    private function get_label($field) {
        if (is_array($field) and isset($field['label'])) {
            return $field['label'];
        }
        return $this->get_label_from_name($field);
    }

    private function get_value($field) {
        if (is_array($field) and isset($field['value'])) {
            return $field['value'];
        }
        return $this->instance !== null ? $this->instance[$field] : '';
    }

    private function get_label_from_name($field) {
        return ucfirst(str_replace('_', ' ', $field));
    }

    private function get_css_class($size, $value) {
        return sprintf('col-%s-%s', $size, $value);
    }
}