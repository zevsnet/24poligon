<?php

namespace SB\Traits;

/**
 * Из-за требования платформы "1С Битрикс" использовать параметр
 * mbstring.func_overload = 2 на проектах в кодировке UTF-8, обработка
 * бинарных строк перестает быть корректной.
 *
 * Чтобы обойти эту проблему необходимо временно переключать кодировку
 * на однобайтовую при работе с библиотеками, модулями и функциями,
 * принимающими на вход бинарные данные (PHPExcel, Predis и прочие).
 *
 */
trait BinarySafe
{
    protected static $isBinarySafetyEnabled = false;

    protected static $originalEncoding = 'utf-8'; // Значение по умолчанию

    protected static function enableBinarySafety()
    {
        if (static::$isBinarySafetyEnabled === false) {
            static::$isBinarySafetyEnabled = true;
            static::$originalEncoding = mb_internal_encoding();
            mb_internal_encoding('latin1');
        }
    }

    protected static function disableBinarySafety()
    {
        mb_internal_encoding(static::$originalEncoding);
        static::$isBinarySafetyEnabled = false;
    }

    protected static function executeInBinarySafeEnvironment($callback)
    {
        static::enableBinarySafety();

        try {
            return $callback();
        } finally {
            static::disableBinarySafety();
        }
    }
}