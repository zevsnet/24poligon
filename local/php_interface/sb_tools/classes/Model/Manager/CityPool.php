<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 16.03.2018
 * Time: 9:30
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Model\Manager;


use SB\Exception\InvalidArgumentException;
use SB\Exception\LogicException;
use SB\Model\Collection;

/**
 * Набор городов
 * @see \SB\Manager\City
 *
 * Class CityPool
 * @package SB\Model\Manager
 * @property City[] $collection
 * @method City[] getIterator()
 */
class CityPool extends Collection
{
    protected $hasDefault = false;
    protected $defaultKey;

    /**
     * добавление города в коллекцию
     * @param string $cityCode
     * @param string $cityName
     * @param bool $default
     * @return $this
     * @throws LogicException
     */
    public function add(string $cityCode, string $cityName = '', bool $default = false): self
    {
        $this->offsetSet(null, new City($cityCode, $cityName, $default));
        return $this;
    }

    /**
     * @param mixed $offset
     * @param City $value
     * @throws InvalidArgumentException
     * @throws LogicException
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof City) {
            throw new InvalidArgumentException('"value" должен быть типа "\SB\Model\Manager\City"');
        }
        parent::offsetSet($offset, $value);
        $this->checkDefault();
    }

    /**
     * получение города по умолчанию
     * @return City|null
     */
    public function getDefault()
    {
        return $this->hasDefault ? $this->offsetGet($this->defaultKey) : null;
    }

    /**
     * получить город по коду
     * @param string $code
     * @return null|City
     */
    public function getByCode(string $code)
    {
        foreach ($this as $city) {
            if(strtolower($city->getCode()) === strtolower($code)) {
                return $city;
            }
        }
        return null;
    }

    /**
     * получить город по названию
     * @param string $name
     * @return null|City
     */
    public function getByName(string $name = '')
    {
        foreach ($this as $city) {
            if(strtolower($city->getName()) === strtolower($name)) {
                return $city;
            }
        }
        return null;
    }

    /**
     * Поиск города по умолчанию и проверка чтобы было не больше одного
     */
    protected function checkDefault()
    {
        $this->hasDefault = false;
        foreach ($this as $key => $city) {
            if(!$city->isDefault()) {
                continue;
            }

            if($this->hasDefault) {
                throw new LogicException('городов по умолчанию больше одного');
            }

            $this->hasDefault = true;
            $this->defaultKey = $key;
        }
    }
}