<?php

namespace App\Libraries;

trait CacheMethods {
    /**
     * Cache store for class
     */
    protected $_cache = [];  // TODO: change to static property, for meta maintenance

    /**
     * Hace un singleton de métodos llamados como propiedades
     */
    public function __get($property) {
        try {
            if (!isset($_cache['_property__' . $property])) {
                $value_from_parent = parent::__get($property);
                $_cache['_property__' . $property] = $value_from_parent;
            }
            return $_cache['_property__' . $property];
        } catch (\Exception $e) {}

        if (method_exists($this, $property)) {
            if (!isset($_cache['_property__' . $property])) {
                $_cache['_property__' . $property] = $this->$property();
            }
            return $_cache['_property__' . $property];
        }
        return parent::__get($property);
    }

    /**
     * Hace un singleton de los retornos de los métodos siempre
     * que tengan los mismos parámetros, si no lo vuelve a llamar.
     * 
     * NOTA: los métodos deben ser privados
     */
    public function __call($method, $parameters) {
        if (method_exists($this, $method)) {
            if (!isset($_cache['_method__' . $method])) {
                $_cache['_method__' . $method] = [
                    '@parameters' => $parameters,
                    '@response' => call_user_func_array([$this, $method], $parameters)
                ];
            } else if ($_cache['_method__' . $method]['@parameters'] !== $parameters) {
                $_cache['_method__' . $method] = [
                    '@parameters' => $parameters,
                    '@response' => call_user_func_array([$this, $method], $parameters)
                ];
            }
            return $_cache['_method__' . $method]['@response'];
        }
        return parent::__call($method, $parameters);
    }
}