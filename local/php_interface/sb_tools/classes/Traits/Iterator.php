<?php

namespace SB\Traits;

/**
 * Trait Iterator
 * @package SB\Traits
 */
trait Iterator
{
    protected $container = [];
    protected $position = 0;
    protected $arExclude = ['container', 'position', 'arExclude'];

    public function reset()
    {
        $arFields = get_object_vars($this);
        foreach ($this->arExclude as $name) {
            unset($arFields[$name]);
        }

        $this->container = array_values($arFields);
    }

    public function rewind()
    {
        $this->reset();
        $this->position = 0;
    }

    public function current()
    {
        return $this->container[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->container[$this->position]);
    }
}