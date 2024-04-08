<?php

namespace debugger;

define('SHIFT_STRING', '  ');

class dumper
{
    private static $iLevel = 0; // For recursion checking
    private static $aObjects = array(); // For recursion checking

    public static function dump($aArgs)
    {
        self::$iLevel = 0;
        self::$aObjects = array();

        print '<pre class="ls-dump prettyprint" style="margin:5px;padding:5px;border:1px #dd0000 solid; background-color: #fff; text-align: left;">' . PHP_EOL;

        foreach ($aArgs as &$aArg) {
            print self::get_item_dump($aArg) . PHP_EOL;
        }

        print '</pre>';
    }

    public static function dumpTrace($aArgs)
    {
        $arDebug = debug_backtrace();

        array_shift($arDebug);
        $arDebugItem = array_shift($arDebug);

        $fileLine = 'file: ' . $arDebugItem['file'] . ' line: ' . $arDebugItem['line'];

        if ($arDebugItem = array_shift($arDebug)) {
            if (!empty($arDebugItem['class'])) {
                $functionLine = 'method: ' . $arDebugItem['class'] . '::' . $arDebugItem['function'];
            } else {
                $functionLine = 'function: ' . $arDebugItem['function'];
            }
        }

        self::$iLevel = 0;
        self::$aObjects = array();

        print '<pre class="ls-dump prettyprint" style="margin:5px;padding:5px;border:1px #dd0000 solid; background-color: #fff; text-align: left;">' . PHP_EOL;
        print '<div style="color: #D00;">' . $fileLine . '</div>';
        print '<div style="color: #D00;">' . $functionLine . '</div>';
        foreach ($aArgs as &$aArg) {
            print self::get_item_dump($aArg) . PHP_EOL;
        }

        print '</pre>';
    }

    private static function get_item_dump(&$mItem)
    {
        if (is_bool($mItem)) {
            return $mItem ? 'TRUE' : 'FALSE';
        } else {
            if (is_int($mItem)) {
                return $mItem;
            } else {
                if (is_float($mItem)) {
                    return $mItem;
                } else {
                    if (is_null($mItem)) {
                        return 'NULL';
                    } else {
                        if (is_string($mItem)) {
                            return '\'' . htmlspecialchars($mItem) . '\'';
                        } else {
                            if (is_array($mItem)) {
                                return 'Array' . PHP_EOL . self::get_array_dump($mItem);
                            } else {
                                if (is_object($mItem)) {

                                    // Add object to current level
                                    self::$aObjects[self::$iLevel][] = $mItem;

                                    if (self::$iLevel > 0) {
                                        // Search object in previous levels
                                        for ($i = self::$iLevel - 1; $i >= 0; --$i) {
                                            if (in_array($mItem, self::$aObjects[$i])) {
                                                $sOutput = '*** RECURSION *** (' . get_class($mItem) . ' Object)';
                                                break;
                                            }
                                        }
                                    }

                                    ++self::$iLevel;

                                    if (!isset($sOutput)) {
                                        $sOutput = print_r($mItem, true);
                                    }

                                    --self::$iLevel;

                                    // Clean level
                                    unset(self::$aObjects[self::$iLevel]);

                                    // Show output
                                    return $sOutput;
                                } else {
                                    if (is_resource($mItem)) {
                                        return 'Resource #' . intval($mItem) . ' of type (' . get_resource_type($mItem) . ')';
                                    } else {
                                        return '(unknown) ' . $mItem;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * {
     *   [1] => ...
     *   [2] => ...
     * }
     */
    private static function get_array_dump(&$aItem)
    {
        $sDump = '(' . PHP_EOL;

        foreach ($aItem as $mKey => $mValue) {
            $sKey = SHIFT_STRING . '[' . self::get_item_dump($mKey) . '] => ';
            $sValue = self::get_item_dump($mValue);

            $sValue = self::add_shift_if_multiline($sValue, SHIFT_STRING . SHIFT_STRING);

            // Concat dump
            $sDump .= $sKey . $sValue . PHP_EOL;
        }

        $sDump .= ')';

        return $sDump;
    }

    /**
     * @param $mItem
     *
     * @return string
     * @deprecated
     */
    private static function get_object_dump($mItem)
    {
        $oReflect = new \ReflectionClass($mItem);

        $aProps = $oReflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);

        $aDump = array();

        foreach ($aProps as $oProp) {
            if ($oProp->isStatic()) {
                continue;
            }

            $oProp->setAccessible(true);
            $mValue = $oProp->getValue($mItem);

            $aDump[$oProp->getName()] = $mValue;
        }

        foreach ($mItem as $key => $item) {
            $aDump[$key] = $item;
        }

        return self::get_array_dump($aDump);
    }

    private static function add_shift_if_multiline($sText, $sShift)
    {
        $aText = explode(PHP_EOL, $sText);

        if (count($aText) > 1) {
            // Skip first param
            $sText = array_shift($aText);

            foreach ($aText as $sTextItem) {
                $sText .= PHP_EOL . $sShift . $sTextItem;
            }
        }

        return $sText;
    }
}