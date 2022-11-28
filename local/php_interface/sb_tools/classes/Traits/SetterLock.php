<?php

namespace SB\Traits;

use SB\Exception;

/**
 * Trait SetterLock
 * Определяет метод __set
 * Открывает запись защищенных свойств
 * При попытке доступа к несуществующему или защищенному свойству выкидывает исключение
 * @package SB
 */
trait SetterLock
{
    /**
     * @param string $name
     * @param mixed $value
     * @return mixed
     * @throws Exception\ForClass\ForProperty\NotAccess
     * @throws Exception\ForClass\ForProperty\NotExist
     */
    public function __set(string $name, $value)
    {
        if (isset($this->$name)) {
            throw new Exception\ForClass\ForProperty\NotAccess($name, static::class);
        }

        throw new Exception\ForClass\ForProperty\NotExist($name, static::class);
    }
}