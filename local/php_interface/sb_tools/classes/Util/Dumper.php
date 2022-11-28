<?php

namespace SB\Util;

\define('SHIFT_STRING', '  ');

/**
 * Class Dumper
 * @package SB\Util
 */
class Dumper
{
    /** @var string - классы 'pre' */
    protected static $preClasses = 'ls-dump prettyprint';
    /** @var string - стили 'pre' */
    protected static $preStyle = 'margin:5px;padding:5px;border:1px #dd0000 solid; background-color: #fff; text-align: left;';
    /** @var bool - показывать ли путь до файла */
    protected static $fileLineShow = true;
    /** @var string - стили для пути */
    protected static $fileLineStyle = 'color: #D00;';
    /** @var bool - показывать ли имя функции/класса */
    protected static $functionLineShow = true;
    /** @var string - стили для функции/класса */
    protected static $functionLineStyle = 'color: #D00;';

    /**
     * выводит дамп входных аргументов
     * @param array $aArgs
     */
    public static function dumpTrace(array $aArgs = []) {
        $arDebug = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3);
        array_shift($arDebug);
        $arDebugItem = array_shift($arDebug);
        $fileLine = 'file: ' . $arDebugItem['file'] . ' line: ' . $arDebugItem['line'];
        $functionLine = '';

        if ($arDebugItem = array_shift($arDebug)) {
            $functionLine = empty($arDebugItem['class']) ? $functionLine = 'function: ' . $arDebugItem['function'] : 'method: ' . $arDebugItem['class'] . '::' . $arDebugItem['function'];
        }

        print '<pre class="' . static::$preClasses . '" style="' . static::$preStyle . '">' . PHP_EOL;
        if(static::$fileLineShow) {
            print '<div style="' . static::$fileLineStyle . '">' . $fileLine . '</div>';
        }
        if(static::$functionLineShow) {
            print '<div style="' . static::$functionLineStyle . '">' . $functionLine . '</div>';
        }
        foreach ($aArgs as $aArg) {
            print static::prepareItem($aArg) . PHP_EOL;
        }

        print '</pre>';
    }

    /**
     * препарирует элемент
     * @param $item
     * @return mixed|string
     */
    protected static function prepareItem($item) {
        switch (\gettype($item)) {
            case 'boolean':
                return $item ? 'TRUE' : 'FALSE';
            case 'string':
                return '\'' . htmlspecialchars($item) . '\'';
            case 'integer':
            case 'double':
                return $item;
            case 'NULL':
                return 'NULL';
            case 'resource':
                return 'Resource #' . (int)$item . ' of type (' . get_resource_type($item) . ')';
            case 'object':
                return print_r($item, true);
            case 'array' :
                $result = '(' . PHP_EOL;

                foreach ($item as $key => $value) {
                    $sKey = SHIFT_STRING . '[' . static::prepareItem($key) . '] => ';
                    $sValue = static::prepareItem($value);
                    $sValue = str_replace(PHP_EOL, PHP_EOL . SHIFT_STRING . SHIFT_STRING, $sValue);

                    // Concat dump
                    $result .= $sKey . $sValue . PHP_EOL;
                }

                $result .= ')';
                return 'Array' . PHP_EOL . $result;
            default:
                return '(unknown) ' . $item;
        }
    }
}