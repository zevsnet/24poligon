<?php

namespace SB\Exception\ForClass;

use SB\Exception\ForClass;
use \Throwable;

/**
 * Class ForProperty
 * @package SB\Exception\ForClass
 */
class ForProperty extends ForClass
{
    /** @var string $propertyName */
    protected $propertyName = '';

    /**
     * ForProperty constructor.
     * @param string $propertyName
     * @param string $className
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($propertyName, $className, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->propertyName = $propertyName;
        parent::__construct($className, $message, $code, $previous);
    }
}