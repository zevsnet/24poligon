<?php

namespace SB\Traits;

/**
 * Trait CheckFields
 * Определяет методы __set и __get
 * Открывает чтение защищенных свойств
 * При попытке доступа к несуществующему или защищенному свойству выкидывает исключение
 * @package SB
 */
trait CheckFields
{
    use SetterLock, Getter;
}