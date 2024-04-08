<?php

/**
 * debug class, just for convinient
 * @author nfzakirov
 **/

include_once 'classes/dump.php';

class _
{
    protected $counter;
    protected $prev_time;

    protected static $prev_time_simple;
    protected static $i = 1;
    protected static $memory;
    protected static $sEndOfMessageSymbol = PHP_EOL;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * Dump
     */
    static function d()
    {

            \debugger\dumper::dumpTrace(func_get_args());

    }


    /**
     * Dump & Die
     */
    static function dd()
    {
        \debugger\dumper::dumpTrace(func_get_args());
        die();
    }

    /**
     * Дамп в консоль браузера. Обязательно должна быть подключена jQuery
     *
     * @param $mData
     */
    public static function dJS($mData)
    {
        $json = json_encode($mData);

        echo "<script type='text/javascript'>
                jQuery(document).ready(function(){
                    console.log($json);
                });
            </script>";
    }

    static function get_caller_info($bFullFilePath = false)
    {
        $sCalled = '';
        $sFunction = '';
        $sFile = '';
        $iLine = '';
        $sClass = '';
        $aTrace = debug_backtrace();

        if (isset($aTrace[1])) {
            $iLine = isset($aTrace[1]['line']) ? $aTrace[1]['line'] : '';
            $sFile = isset($aTrace[1]['file']) ? $aTrace[1]['file'] : '';
        }

        if (isset($aTrace[2])) {
            $sFunction = $aTrace[2]['function'];

            $sAction = substr($sFunction, 0, 7);
            if ($sAction == 'include' || $sAction == 'require') {
                $sFunction = '';
            }
        }
        if (isset($aTrace[3]['class'])) {
            $sClass = $aTrace[3]['class'];
            $sFunction = $aTrace[3]['function'];
            $sFile = $aTrace[2]['file'];
        } else {
            if (isset($aTrace[2]['class'])) {
                $sClass = $aTrace[2]['class'];
                $sFunction = $aTrace[2]['function'];
                $sFile = $aTrace[1]['file'];
            }
        }

        if (!$bFullFilePath and $sFile != '') {
            $sFile = basename($sFile);
        }

        $sCalled = $sFile . ($iLine ? ":" . $iLine : '') . ' - ';
        $sCalled .= ($sClass) ? $sClass . "->" : "";
        $sCalled .= ($sFunction) ? $sFunction . "()" : "";

        return $sCalled;
    }

    static function dump_caller_info($bFullFilePath = false)
    {
        self::d(self::get_caller_info());
    }

    /**
     * Time simple (in milliseconds).
     * First call will start timer.
     */
    static function ts($sSomeText = '')
    {
        // On first call
        if (!self::$prev_time_simple) {
            self::$prev_time_simple = microtime(true);

            //print '<pre>Timer started</pre>' . PHP_EOL;
            return;
        }

        $sTime = (microtime(true) - self::$prev_time_simple) * 1000;
        print '<pre>' . ($sSomeText ? $sSomeText . ': ' : '') . number_format($sTime, 3, ',', ' ') . '</pre>' . PHP_EOL;
    }

    static function get_calling_method_name()
    {
        $e = new Exception();
        $trace = $e->getTrace();
        //position 0 would be the line that called this function so we ignore it
        $last_call = $trace[1]['file'] . ' - ' . $trace[1]['line'];

        return $last_call;
    }

    /**
     * Using:
     *
     * _::MemoryUsageReset();
     * //
     * // Write code
     * //
     * _::MemoryUsageShow();
     * //
     * // Write code
     * //
     * _::MemoryUsageShow('Last call');
     *
     */
    static function memory_usage_show($sPreText = '')
    {
        if ($sPreText) {
            printf($sPreText . '|');
        }
        unset($sPreText);
        $iDiff = memory_get_usage() - self::$memory;
        $iDiff = $iDiff / 1024; // kB
        printf('Memory usage: ' . number_format($iDiff, 2, '.', '\'') . ' kB' . self::$sEndOfMessageSymbol);
        flush();
    }

    static function memory_usage_reset($sEndOfMessageSymbol = PHP_EOL)
    {
        // Обязательно два раза, т.к. часть уходит на объявление
        self::$memory = memory_get_usage();
        self::$memory = memory_get_usage();

        // Установим символ конца сообщения
        self::$sEndOfMessageSymbol = $sEndOfMessageSymbol;
    }

}