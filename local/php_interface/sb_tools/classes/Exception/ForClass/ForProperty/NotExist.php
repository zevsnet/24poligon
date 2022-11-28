<?php

namespace SB\Exception\ForClass\ForProperty;

use SB\Exception\ForClass\ForProperty;
use \Throwable;

/**
 * Class NotExist
 * @package SB\Exception\ForClass\ForProperty
 */
class NotExist extends ForProperty
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
        $messageText = "Свойство '{$this->propertyName}' не объявлено";
        if (!empty($this->message)) {
            $this->message = $messageText . '. ' . $this->message;
        }
    }
}
