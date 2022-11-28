<?php

namespace SB\Traits;

/**
 * Trait Singleton
 * Реализует паттерн синглтон
 * @package SB
 */
trait Singleton
{
    /** @var static|null Объект класса */
    protected static $instance;

    /**
     * Создает и возвращает объект класса
     * @param array ...$argument
     * @return static
     */
    public static function getInstance(...$argument)
    {
        if (static::$instance === null) {
            static::$instance = new static(...$argument);
        }

        return static::$instance;
    }

    public static function reset()
    {
        static::$instance = null;
    }

    protected function __construct(...$argument)
    {
    }
}