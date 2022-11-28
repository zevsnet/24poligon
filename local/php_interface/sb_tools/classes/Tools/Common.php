<?php

namespace SB\Tools;

/**
 * Class Common
 * @package SB\Tools
 */
class Common
{
    /**
     * Возвращает массив только из искомых ключей $search в $arIn
     * @param array $arIn
     * @param string|array $search
     * @return array
     */
    public static function extractArray(array $arIn, $search): array
    {
        $search = \is_array($search) ? $search : array_map('trim', explode(',', $search));
        if(empty($search)) {
            return [];
        }

        $search = array_flip($search);

        return array_intersect_key($arIn, $search);
    }

    /**
     * Делает трим для многомерного массива
     *
     * @param array $arRes
     * @param string $charList
     * @return array
     */
    public static function arrayTrim(array $arRes, string $charList = " \t\n\r\0\x0B"): array
    {
        array_walk_recursive($arRes, function(&$item, $key, $charList) {
            $item = trim($item, $charList);
        }, $charList);

        return $arRes;
    }

    /**
     * Шифрует по ключу, и переводит в base64
     *
     * @param $key - ключ шифрования
     * @param $text - шифруемый текст
     *
     * @return string
     */
    public static function fastEncrypt($key, $text, $complate = true)
    {
        if ($complate) {
            $len = strlen($key);
            if ($len > 32) {
                $key = substr($key, 0, 32);
            } else {
                $key = str_pad($key, 32);
            }
        }

        $encodedText = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $text, MCRYPT_MODE_ECB);

        return base64_encode($encodedText);
    }

    /**
     * Расшировывает данные зашифрованные функцией fastEncrypt
     *
     * @param $key - ключ шифрования
     * @param $code - шифрованные данные
     *
     * @return string
     */
    public static function fastDecrypt($key, $code, $complate = true)
    {
        if ($complate) {
            $len = strlen($key);
            if ($len > 32) {
                $key = substr($key, 0, 32);
            } else {
                $key = str_pad($key, 32);
            }
        }

        $code = base64_decode($code);

        return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $code, MCRYPT_MODE_ECB);
    }

    /**
     * Конвертирует из Системы счисления алфавита $arAlphabet в десятичную
     *
     * @param $arAlphabet
     * @param $value
     *
     * @return int|mixed
     */
    public static function convertToDec($arAlphabet, $value)
    {
        $alphabetSize = count($arAlphabet);
        $valueDec = 0;

        $n = strlen($value);

        for ($i = 0; $i < $n; $i++) {
            $char = substr($value, $i, 1);

            $index = array_search($char, $arAlphabet);
            $valueDec += $index * pow($alphabetSize, $n - $i - 1);
        }

        return $valueDec;
    }

    /**
     * Конвертирует из десятичную в систему счисления алфавита $arAlphabet
     *
     * @param $arAlphabet
     * @param $value
     *
     * @return string
     */
    public static function decToAlphabet($arAlphabet, $value)
    {
        $alphabetSize = count($arAlphabet);

        $valueDiv = floor($value / $alphabetSize);
        $valueMod = $value % $alphabetSize;

        if ($valueDiv) {
            $first = self::decToAlphabet($arAlphabet, $valueDiv);
        }

        return $first . $arAlphabet[$valueMod];
    }

    /**
     * Выполняет подключение файла, но не выводит на экран, а возвращает получившийся результат тебе 2
     * @param string $filePath
     * @param array $arTplParams
     * @return string
     */
    public static function renderTpl(string $filePath, array $arTplParams = []): string
    {
        ob_start();

        if ($arTplParams) {
            extract($arTplParams, EXTR_OVERWRITE);
        }

        /** @noinspection PhpIncludeInspection */
        include $filePath;

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @param array $arResult
     * @param array $arKeys массив ключей. Доступные значения id, parent_id, children. Могут быть переназначены на нужные значения, по дефолту ID, PARENT_ID, CHILDREN
     * @param callable $functionFilter может быть передана функция (можно передавать строку, либо саму функцию), в функцию будет передан один параметр, текущий элемент. При возвращении функцией try удаляет элемент и всех его детей из результирующего массива
     *
     * @return array
     */
    public static function getTree(array $arResult = array(), array $arKeys = array(), callable $functionFilter = null)
    {
        $arKeys += array('id' => 'ID', 'parent_id' => 'PARENT_ID', 'children' => 'CHILDREN');

        $arTree = array(); //массив будущего дерева
        $arLink = array();

        foreach ($arResult as $key => $arItem) {
            if (is_callable($functionFilter) && $functionFilter($arItem)) {
                unset($arResult[$key]);
                continue;
            }

            if ($arItem[$arKeys['parent_id']]) {
                continue;
            }

            $arTree[$arItem[$arKeys['id']]] = $arItem;
            $arLink[$arItem[$arKeys['id']]] = &$arTree[$arItem[$arKeys['id']]];
        }

        foreach ($arResult as $arItem) {
            if (!$arItem[$arKeys['parent_id']])
                continue;

            $arLink[$arItem[$arKeys['parent_id']]][$arKeys['children']][$arItem[$arKeys['id']]] = $arItem;

            foreach ($arLink as $link) {
                if ($link[$arKeys['parent_id']] === $arItem[$arKeys['id']]) {
                    $arLink[$arItem[$arKeys['parent_id']]][$arKeys['children']][$arItem[$arKeys['id']]][$arKeys['children']][$link[$arKeys['id']]] = $link;
                }
            }

            $arLink[$arItem[$arKeys['id']]] = &$arLink[$arItem[$arKeys['parent_id']]][$arKeys['children']][$arItem[$arKeys['id']]];
        }

        return $arTree;
    }

    /**
     * скидывает буферизацию вывода
     */
    public static function removeBuffer()
    {
        while (ob_get_level()) {
            ob_end_flush();
        }
    }
}