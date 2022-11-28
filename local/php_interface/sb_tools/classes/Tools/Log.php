<?php

namespace SB\Tools;

use SB\Exception;

/**
 * Class Log
 * @package SB\Tools
 */
class Log
{
    const DEFAULT_MODE = 'ab+';
    protected $filePath = '';
    protected $fileRecourse;
    protected $mode = 'ab+';

    /**
     * Log constructor.
     * @param $filePath
     * @param string $mode
     * @throws Exception
     */
    public function __construct($filePath, $mode = self::DEFAULT_MODE)
    {
        try {
            $arFilePath = explode(DIRECTORY_SEPARATOR, $filePath);
            array_pop($arFilePath);
            $dirPath = implode(DIRECTORY_SEPARATOR, $arFilePath);

            if (!file_exists($dirPath) && !mkdir($dirPath, 0755, true) && !is_dir($dirPath)) {
                throw new Exception('Не удалось создать папку.');
            }

            $this->filePath = $filePath;
            if (!empty($mode)) {
                $this->mode = $mode;
            }
        } catch (Exception $ex) {
            throw new Exception('Не удалось создать лог. ' . $ex->getMessage());
        }
    }

    /**
     * открывает файл
     * @throws Exception
     */
    public function openFile()
    {
        $this->fileRecourse = fopen($this->filePath, $this->mode);

        if (!$this->fileRecourse) {
            throw new Exception('Не удалось открыть файл.');
        }
    }

    /**
     * добавляет запись с переносом
     * @param $message
     * @throws Exception
     */
    public function add($message)
    {
        if (!$this->fileRecourse) {
            $this->openFile();
        }
        fwrite($this->fileRecourse, static::makeRecord($message));
    }

    /**
     * добавляет запись без переноса
     * @param $message
     * @throws Exception
     */
    public function addInline($message)
    {
        if (!$this->fileRecourse) {
            $this->openFile();
        }
        fwrite($this->fileRecourse, static::makeRecordInline($message));
    }

    /**
     * формирует запись с переносом
     * @param $message
     * @return string
     */
    protected static function makeRecord($message): string
    {
        return date('Y-m-d H:i:s') . "\n" . print_r($message, true) . "\n" . '----------' . "\n";
    }

    /**
     * формирует запись без переноса
     * @param $message
     * @return string
     */
    protected static function makeRecordInline($message): string
    {
        return '[' . date('Y-m-d H:i:s') . '] ' . $message . "\n";
    }

    public function printFile()
    {
        echo '<pre>';
        echo file_get_contents($this->filePath);
        echo '</pre>';
    }

    public function getFileContent()
    {
        return file_get_contents($this->filePath);
    }

    /**
     * закрывает файл
     */
    public function __destruct()
    {
        if ($this->fileRecourse) {
            fclose($this->fileRecourse);
        }
    }

    /**
     * пишет в файл с переносом строки
     * @param $filePath - имя файла
     * @param $message - текст
     * @throws Exception
     */
    public static function addToLog($filePath, $message)
    {
        $obLog = new self($filePath);
        $obLog->add($message);
        unset($obLog);
    }

    /**
     * пишет в файл без переноса строки
     * @param $filePath - имя файла
     * @param $message - текст
     * @throws Exception
     */
    public static function addToLogInline($filePath, $message) {
        $obLog = new self($filePath);
        $obLog->addInline($message);
        unset($obLog);
    }


}