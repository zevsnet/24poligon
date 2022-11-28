<?php

namespace SB\Tools;

use SB\Exception;
use SB\Tools;

/**
 * Class ForString
 * @package SB\Tools
 */
class ForString
{
    /**
     * Выполняет транслитерацию с заданными параметрами
     * @param string $text
     * @param array $arParams
     * @return mixed
     * @internal param int $count - количество
     * @internal param array $arWords
     */
    public static function transliterate(string $text, array $arParams = [])
    {
        $arParams = array_merge([
            'max_len' => '100',   // обрезает символьный код до 100 символов
            'change_case' => 'L',     // буквы преобразуются к нижнему регистру
            'replace_space' => '_',     // меняем пробелы на нижнее подчеркивание
            'replace_other' => '_',     // меняем левые символы на нижнее подчеркивание
            'delete_repeat_replace' => 'true',  // удаляем повторяющиеся нижние подчеркивания
            'use_google' => 'false', // использование Google
        ], $arParams);

        return \CUtil::translit($text, 'ru', $arParams);
    }

    /**
     * Возвращает окончание для слова в зависимости от количества
     * @param int $count - количество
     * @param array $arWords
     * @return mixed
     * @throws Exception
     */
    public static function getEndWord(int $count = 1, array $arWords = ['товар', 'товара', 'товаров'])
    {
        if (count($arWords) !== 3) {
            throw new Exception('Не верная длина массива');
        }

        $number = abs($count % 100);
        $number = $number < 20 ? $number : $number % 10;
        if ($number === 1) {
            return current($arWords);
        }
        if ($number > 1 && $number < 5) {
            return next($arWords);
        }

        return end($arWords);
    }

    public static function removeMultipleSpaces(string $text)
    {
        return preg_replace("/ {2,}/", ' ', $text);
    }

    public static function randomNumberCode($length = 6)
    {
        return randString($length, ['0123456789']);
    }

    /**
     * Проверяет телефон и приводит к правильному виду
     * Номер состоит из 11 цифр, начинается с 7
     * 73334445566
     * @param $phone
     * @return string
     * @throws Exception
     */
    public static function validPhone($phone): string
    {
        try {
            # удаляем лишние пробелы
            $phone = trim($phone);
            # удаляем из номера все кроме цифр
            $phone = preg_replace('/[\D]/', '', $phone);
            if (strlen($phone) === 11) {
                $phone = (string)substr($phone, 1);
            }
            if (strlen($phone) !== 10) {
                throw new Exception('Длина не равна 10 символам');
            }
            if ((int)$phone[0] !== 9) {
                throw new Exception('Первая цифра не 9');
            }
            return $phone;
        } catch (\Exception $exception) {
            throw new Exception('Номер не корректен.' . $exception->getMessage(), 0, $exception);
        }
    }

    public static function isPhoneValid(string $phone): bool
    {
        try {
            if (strlen($phone) !== 10) {
                throw new Exception('Длина не равна 10 символам');
            }
            if ((int)$phone[0] !== 9) {
                throw new Exception('Первая цифра не 9');
            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}