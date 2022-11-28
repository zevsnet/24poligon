<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 16.03.2018
 * Time: 9:26
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Model\Manager;

/**
 * модель города, предназначено для работы совместно с CityPool
 *
 * @see CityPool
 *
 * Class City
 * @package SB\Model\Manager
 */
class City
{
    /** @var string код города */
    protected $code;
    /** @var string имя города */
    protected $name;
    /** @var bool флаг по умолчанию */
    protected $default;

    public function __construct(string $code, string $name = '', bool $default = false)
    {
        $this->code = $code;
        $this->name = $name;
        $this->default = $default;
    }

    /**
     * флаг по умолчанию
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->default;
    }

    /**
     * возвращает код города
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * возвращает имя города
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}