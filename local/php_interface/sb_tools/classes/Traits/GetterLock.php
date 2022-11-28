<?php

namespace SB\Traits;

use SB\Exception;

/**
 * Trait GetterLock
 * Определяет метод __get
 * Открывает чтение защищенных свойтсв
 * При попытке доступа к несуществующему или защищенному свойству выкидывает исключение
 * @package SB
 */
trait GetterLock
{
    /**
     * @param string $name
     * @return mixed
     * @throws Exception\ForClass\ForProperty\NotAccess
     * @throws Exception\ForClass\ForProperty\NotExist
     */
    public function __get(string $name)
    {
        if (isset($this->$name)) {
            throw new Exception\ForClass\ForProperty\NotAccess($name, static::class);
        } else {
            throw new Exception\ForClass\ForProperty\NotExist($name, static::class);
        }
    }
}