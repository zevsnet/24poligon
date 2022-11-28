<?php

namespace SB\Tools;

use SB\Exception\ComposerNotFountException;
use Symfony\Component\VarDumper\VarDumper;

if (!class_exists(VarDumper::class)) {
    throw new ComposerNotFountException('Установите модули (composer install) ');
}

/**
 * Класс для отладки скриптов (не привязан к Bitrix)
 *
 * Сокращенное название класса для вызова - \_::
 *
 * @package SB\Tools
 */
class Dumper
{
    protected $counter;
    protected $prev_time;

    protected static $prev_time_simple;
    protected static $i = 1;
    protected static $memory;
    protected static $sEndOfMessageSymbol = PHP_EOL;

    /**
     * Вывод дампа данных
     *
     * @param array ...$args
     * @return mixed
     */
    public static function d(...$args)
    {
        $count = \count($args);

        foreach ($args as $arg) {
            if (--$count <= 0) {
                break;
            }
            VarDumper::dump($arg);
        }
        return VarDumper::dump(end($args));
    }

    public static function ddev(...$args)
    {
        if ($_REQUEST['DEV'] == 'Y') {
            $count = \count($args);

            foreach ($args as $arg) {
                if (--$count <= 0) {
                    break;
                }
                VarDumper::dump($arg);
            }
            return VarDumper::dump(end($args));
        }
    }


    /**
     * Выводит дамп и умирает
     *
     * @param array ...$args
     */
    public static function dd(...$args)
    {
        static::d(...$args);
        die();
    }

    /**
     * Дамп в консоль браузера
     *
     * @param array $args
     */
    public static function dJS(...$args)
    {
        foreach ($args as $variable) {
            $json = json_encode($variable);

            echo '<script>console.log(' . $json . ')</script>';
        }
    }

    /**
     * Time simple (in milliseconds).
     * First call will start timer.
     *
     * @param string $sSomeText
     * @author D. Panachev <18.11.2013, number_format call added>
     * @author D. Panachev <24.07.2013, Implementation>
     */
    public static function ts($sSomeText = '')
    {
        // On first call
        if (!self::$prev_time_simple) {
            self::$prev_time_simple = microtime(true);
            return;
        }

        $sTime = (microtime(true) - self::$prev_time_simple) * 1000;
        print '<pre>' . ($sSomeText ? $sSomeText . ': ' : '') . number_format($sTime, 3, ',', ' ') . '</pre>' . PHP_EOL;
    }
}