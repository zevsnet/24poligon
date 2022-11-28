<?php

namespace SB\Tools\Output;

use SB\Exception;
use SB\Tools\Output;

class FileOutput extends Output
{
    protected $fh;

    protected $filePath;

    /**
     * Constructor
     *
     * @param $filePath
     * @throws Exception
     */
    public function __construct($filePath)
    {
        $arPath = explode(DIRECTORY_SEPARATOR, $filePath);
        array_pop($arPath);
        $dirPath = implode(DIRECTORY_SEPARATOR, $arPath);
        if(!mkdir($dirPath, 0775, true) && !is_dir($dirPath)) {
            throw new Exception('директория не создана');
        }
        $this->fh = fopen($filePath, 'ab');
        $this->filePath = $filePath;
    }

    public function write($text)
    {
        fwrite($this->fh, $text);
    }

    public function writeln($text)
    {
        fwrite($this->fh, $text . PHP_EOL);
    }

    public function __destruct()
    {
        fclose($this->fh);
    }
}