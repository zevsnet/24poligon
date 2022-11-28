<?php

namespace SB;

use \Throwable;

/**
 * Базовый класс ошибок
 *
 * Описывает критические ошибка, внутренние ошибки PHP
 *
 * @package SB
 */
class Error extends \Error
{
    /**
     * Error constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}