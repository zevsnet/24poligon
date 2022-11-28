<?php

namespace SB\Exception;

use SB\Exception;
use \Throwable;

/**
 * Class ForClass
 * @package SB\Exception
 */
class ForClass extends Exception
{
    /** @var string $className */
    protected $className = '';

    /**
     * ForClass constructor.
     * @param string $className
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($className, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->className = $className;
        parent::__construct($message, $code, $previous);
    }
}