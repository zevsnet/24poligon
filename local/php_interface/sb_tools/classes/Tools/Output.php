<?php

namespace SB\Tools;

abstract class Output
{
    abstract public function write($text);

    abstract public function writeln($text);

}