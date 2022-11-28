<?php

namespace SB\Traits;

use SB\Exception;

/**
 * Trait Setter
 * Определяет метод __set
 * Открывает запись защищенных свойтсв
 * При попытке доступа к несуществующему свойству выкидывает исключение
 * @package SB
 */
trait Setter
{
    /**
     * @param string $name
     * @param mixed $value
     * @throws Exception\ForClass\ForProperty\NotExist
     */
    public function __set(string $name, $value)
    {
        if (isset($this->$name)) {
            $this->$name = $value;
        } else {
            throw new Exception\ForClass\ForProperty\NotExist($name, static::class);
        }
    }
}