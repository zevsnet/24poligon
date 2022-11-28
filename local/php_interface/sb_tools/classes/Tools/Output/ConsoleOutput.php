<?php

namespace SB\Tools\Output;

use SB\Tools\Common;
use SB\Tools\Output;

class ConsoleOutput extends Output
{
    public function __construct($removeBuffer = false)
    {
        if ($removeBuffer) {
            Common::removeBuffer();
        }
    }

    public function write($text)
    {
        echo $text;
    }

    public function writeln($text)
    {
        echo $text . PHP_EOL;
    }
}