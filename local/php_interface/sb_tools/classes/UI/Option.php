<?php

namespace SB\UI;

/**
 * Class Option
 * @package SB\UI
 */
class Option
{
    /** @var string имя опции */
    protected $name = '';

    /** @var mixed данные */
    protected $data = [];

    /**
     * Option constructor.
     * @param string $name
     * @param mixed $data
     */
    public function __construct(string $name, $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * имя опции
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * данные
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}