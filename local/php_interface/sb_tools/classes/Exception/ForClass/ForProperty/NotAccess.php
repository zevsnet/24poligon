<?php

namespace SB\Exception\ForClass\ForProperty;

use SB\Exception\ForClass\ForProperty;
use \Throwable;

/**
 * Class NotAccess
 * @package SB\Exception\ForClass\ForProperty
 */
class NotAccess extends ForProperty
{
    /**
     * NotExist constructor.
     * @param string $propertyName
     * @param string $className
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($propertyName, $className, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($propertyName, $className, $message, $code, $previous);
        $messageText = "Свойство '{$this->propertyName}' не доступно";
        if (!empty($this->message)) {
            $this->message = $messageText . '. ' . $this->message;
        }
    }
}
