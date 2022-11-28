<?php

namespace SB\Model\Vue;

/**
 * Class Application
 * @package SB\Model\Vue
 */
class Application
{
    /** @var string $name Название приложения и свойства window с настройками приложения (camelCase) */
    protected $name = 'vueApplication';

    /** @var string $element ИД DOM элемента для подключения приложения (BEM) */
    protected $element = 'app';

    /** @var string $name Название компонента (camelCase) */
    public $component = 'Hello';

    /** @var array $data Данные компонента */
    public $data = [];

    public function __construct(string $name, string $element)
    {
        $this->name = $name;
        $this->element = $element;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getElement(): string
    {
        return $this->element;
    }
}