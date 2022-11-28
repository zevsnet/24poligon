<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 25.12.2017
 * Time: 8:37
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Bitrix\Tools;

use SB\Exception;
use SB\Util\Dumper;
use SB\Tools\Log;

/**
 * Класс для отладки скриптов
 * Class Debug
 * @package SB\Bitrix\Tools
 */
class Debug
{
    /**
     * Делает дамп только для определенного пользователя
     *
     * @param int $userId - ID пользователя
     * @param $args - переменные для вывода (любое количество)
     */
    public static function userDump(int $userId, ...$args)
    {
        global $USER;
        $currentId = (int)$USER->GetID();
        if ($currentId && $currentId === $userId) {
            Dumper::dumpTrace($args);
        }
    }

    /**
     * Добавляет в лог файла /upload/tmp/logs/$filename
     *
     * @param $filename - имя файла в каталоге /upload/tmp/logs/
     * @param $mData
     *
     * @throws \RuntimeException
     * @throws Exception
     */
    public static function log($filename, $mData)
    {
        if (!$filename) {
            throw new Exception('filename is empty');
        }

        $logDirPath = $_SERVER['DOCUMENT_ROOT'] . '/upload/tmp/logs/';

        if (!file_exists($logDirPath) && !mkdir($logDirPath) && !is_dir($logDirPath)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $logDirPath));
        }

        $logFilePath = $logDirPath . $filename;

//        Log::addToLog($logFilePath, $mData);
    }
}