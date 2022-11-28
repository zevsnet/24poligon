<?php

namespace SB\Traits;

/**
 * Trait Multiton
 * Реализует паттерн "пул одиночек"
 * @package SB
 */
trait Multiton
{
    /** @var static|null $instance массив объектов */
    protected static $instance = [];

    /**
     * Создает и возвращает объект класса
     * @param array ...$argument
     * @return static
     */
    public static function getInstance(...$argument)
    {
        if(static::$instance[static::class] === null) {
            static::$instance[static::class] = new static(...$argument);
        }

        return static::$instance[static::class];
    }

    public static function reset()
    {
        static::$instance[static::class] = null;
    }

    protected function __construct(...$argument)
    {
    }
}