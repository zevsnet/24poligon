<?php

namespace SB\Tools\Output;

use SB\Tools\Output;

class BufferOutput extends Output
{
    protected $buffer = '';

    public function write($text)
    {
        $this->buffer .= $text;
    }

    public function writeln($text)
    {
        $this->buffer .= $text . PHP_EOL;
    }

    public function getBuffer(): string
    {
        return $this->buffer;
    }
}