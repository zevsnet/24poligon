<?php

namespace SB\UI;

use Bitrix\Main\Web\Uri;
use SB\Exception\InvalidArgumentTypeException;
use SB\Model\Collection;

/**
 * Class OptionFactory
 * @package SB\UI
 * @property Option[] $collection
 * @method Option[] getIterator()
 *
 * фабрика для работы с переключаемыми значениями (количество выводимых элементов, сортировка, список/линия для элементов и т.п.)
 */
class OptionPool extends Collection
{
    /** @var OptionPool[] массив объектов инстансов */
    protected static $instances;
    /** @var string имя коллекции */
    protected $name;
    /** @var Uri */
    protected $uri;
    /** @var string префикс для сессионной переменной */
    protected $prefixSession = 'option_';

    /**
     * Cache constructor.
     * @param string $name
     */
    protected function __construct(string $name)
    {
        $this->name = $name;
        global $APPLICATION;
        $this->uri = new Uri($APPLICATION->GetCurUri());
    }

    /**
     * @param string $name
     * @return OptionPool
     */
    public static function getInstance(string $name)
    {
        if (empty(self::$instances[$name])) {
            self::$instances[$name] = new static($name);
        }
        return self::$instances[$name];
    }

    /**
     * добавление элемента в коллекцию
     * @param string|int $key
     * @param mixed $values
     * @return $this
     * @throws InvalidArgumentTypeException
     */
    public function add($key, $values): self
    {
        $this->offsetSet(null, new Option($key, $values));
        return $this;
    }

    /**
     * добавление группы элементов в коллекцию
     * @param array $arData
     * @return $this
     * @throws InvalidArgumentTypeException
     */
    public function addGroup(array $arData = []): self
    {
        foreach ($arData as $key => $values) {
            $this->add($key, $values);
        }
        return $this;
    }

    /**
     * @param string|int $key
     * @param Option $value
     * @throws InvalidArgumentTypeException
     */
    public function offsetSet($key, $value)
    {
        if (!$value instanceof Option) {
            throw new InvalidArgumentTypeException('value', Option::class);
        }
        parent::offsetSet(null, $value);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * возвращает список имён опций
     * @return array
     */
    public function getNames(): array
    {
        $values = [];
        foreach ($this as $option) {
            $values[] = $option->getName();
        }
        return $values;
    }

    /**
     * возвращает выбранное имя
     * @param mixed $defaultName - имя опции по умолчанию
     * @return string|null
     */
    public function getSelectedName($defaultName = null)
    {
        $selected = null;
        $names = $this->getNames();

        if (isset($_REQUEST[$this->getName()])) {
            $selected = $_REQUEST[$this->getName()];
        }

        if (!$selected || !\in_array($selected, $names, true)) {
            $selected = $_SESSION[$this->prefixSession . $this->getName()];
        }

        if (!\in_array($selected, $names, true)) {
            $selected = $defaultName;
        }

        if (!\in_array($selected, $names, true)) {
            $selected = current($names);
        }

        $_SESSION[$this->prefixSession . $this->getName()] = $selected;

        return $selected;
    }

    /**
     * устанавливает выбранное имя опции
     * @param mixed $name
     * @return $this
     */
    public function setSelectedName($name): self
    {
        $name = $this->getSelectedName($name);
        $_SESSION[$this->prefixSession . $this->getName()] = $name;
        return $this;
    }

    /**
     * возвращает выбранную опцию
     * @return null|Option
     */
    public function getSelectedOption()
    {
        $selected = $this->getSelectedName();
        foreach ($this as $option) {
            if ($option->getName() === $selected) {
                return $option;
            }
        }
        return null;
    }

    /**
     * собирает url с указанным значением
     * @param $value
     * @return string
     */
    public function getUrl($value): string
    {
        $this->uri->addParams([$this->getName() => $value]);
        return $this->uri->getPathQuery();
    }
}