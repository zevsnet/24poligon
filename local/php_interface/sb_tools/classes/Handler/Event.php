<?php

namespace SB\Handler;

use SB\Traits\CheckFields;

/**
 * @deprecated
 * Class Event
 * @package SB\Handler
 */
abstract class Event
{
    use CheckFields;

    abstract public function AddEvents();
}