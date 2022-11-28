<?php

namespace SB\Traits;

use SB\Exception;

/**
 * Trait Getter
 * Определяет метод __get
 * Открывает чтение защищенных свойств
 * При попытке доступа к несуществующему свойству выкидывает исключение
 * @package SB
 */
trait Getter
{
    /**
     * @param string $name
     * @return mixed
     * @throws Exception\ForClass\ForProperty\NotExist
     */
    public function __get(string $name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        throw new Exception\ForClass\ForProperty\NotExist($name, static::class);
    }
}