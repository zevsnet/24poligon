<?php

namespace SB\Tools;

/**
 * Class ForArray
 * @package SB\Tools
 */
class ForArray
{
    public static function getMaxElementByKey($arData, $keyName)
    {
        if (isset($arData[0][$keyName])) {
            $maxElement = $arData[0];
            foreach ($arData as $item) {
                if ($maxElement[$keyName] < $item[$keyName]) {
                    $maxElement = $item;
                }
            }

            return $maxElement;
        } else {
            return false;
        }
    }

    public static function getMinElementByKey($arData, $keyName)
    {
        if (isset($arData[0][$keyName])) {
            $maxElement = $arData[0];
            foreach ($arData as $item) {
                if ($maxElement[$keyName] > $item[$keyName]) {
                    $maxElement = $item;
                }
            }

            return $maxElement;
        } else {
            return false;
        }
    }

    /**
     * @param array $array
     * @param string|int $key
     * @return null|mixed
     */
    public static function ejectKey(array &$array, $key)
    {
        if (isset($array[$key])) {
            $item = $array[$key];
            unset($array[$key]);
            return $item;
        }
        return null;
    }
}