<?php

namespace SB;

use \Throwable;

/**
 * Базовый класс исключений
 *
 * Описывает общие ошибки работы скриптов
 *
 * @package SB
 */
class Exception extends \Exception
{
    /**
     * Exception constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}