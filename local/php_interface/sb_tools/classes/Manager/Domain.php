<?php

namespace SB\Manager;

use Bitrix\Main\Application;
use Bitrix\Main\Service\GeoIp\Manager;
use Bitrix\Main\Web\Cookie;
use SB\Bitrix\Tools;
use SB\Exception;
use SB\Model\Manager\City;
use SB\Model\Manager\CityPool;
use SB\Traits\Singleton;

/**
 * Класс для работы с городами
 *
 * Class Domain
 * @package SB\Manager
 * @example Manager/DomainClass.php 2 пример класса для реализации
 * @example Manager/Domain.php 2 пример работы с городами
 */
abstract class Domain
{
    use Singleton;
    /** @var string поддомен */
    protected $subDomain;
    /** @var string имя cookie */
    protected $cookieName = 'SB_CITY_MANAGER';
    /** @var bool флаге редиректа в админке */
    protected $adminRedirect = false;
    /** @var City объект текущего города */
    protected $city;
    /** @var CityPool */
    protected $cityList;
    /** @var \Bitrix\Main\Context контекст */
    protected $context;

    /**
     * Domain constructor.
     * @throws Exception
     */
    protected function __construct()
    {
        try {
            if (!$this->adminRedirect && $this->isAdminSection()) {
                return;
            }

            $this->context = Application::getInstance()->getContext();
            $this->getCityList();

            if (\count($this->getCityList()) === 0) {
                throw new Exception\LogicException('Список городов пуст');
            }

            $this->subDomain = $this->getSubDomain();

            if (!$this->detectCity()) {
                throw new Exception\LogicException('Не удалось установить город');
            }

            $cookie = new Cookie($this->cookieName, $this->getCity()->getCode());
            $cookie->setDomain($this->getServerName());
            $this->context->getResponse()->addCookie($cookie)->flush('');

            if (!$this->checkRedirect()) {
                return;
            }

            LocalRedirect($this->buildUrl($this->getCity()->getCode()), true);
        } catch (\Throwable $throwable) {
            throw new Exception('Не удалось инициализировать объект ' . __CLASS__ . '. ' . $throwable->getMessage(), 0, $throwable);
        }
    }

    /**
     * получение списка городов
     * @return CityPool
     */
    public function getCityList(): CityPool
    {
        if ($this->cityList === null) {
            $this->cityList = $this->loadCityList();
        }
        return $this->cityList;
    }

    /**
     * получить выбранный город
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * редирект на другой город
     * @param City $city
     */
    public function goToCity(City $city)
    {
        if ($city->getCode() !== $this->getCity()->getCode()) {
            LocalRedirect($this->buildUrl($city->getCode()), true);
        }
    }

    /**
     * редирект на другой город по коду
     * @uses goToCity
     * @param string $cityCode
     */
    public function goToCityByCode(string $cityCode)
    {
        if ($city = $this->getCityList()->getByCode($cityCode)) {
            $this->goToCity($city);
        }
    }

    /**
     * загрузка списка городов
     * @return CityPool
     */
    abstract protected function loadCityList(): CityPool;

    /**
     * Определение города
     * @return bool
     */
    protected function detectCity(): bool
    {
        if ($this->city = $this->getCityList()->getByCode($this->subDomain)) {
            return true;
        }

        global $APPLICATION;
        if ($this->city = $this->getCityList()->getByCode($APPLICATION->get_cookie($this->cookieName))) {
            return true;
        }

        if ($this->city = $this->getCityByGeo()) {
            return true;
        }

        if ($this->city = $this->getCityList()->getDefault()) {
            return true;
        }

        return false;
    }

    /**
     * Определение города по ип
     * @return null|\SB\Model\Manager\City
     */
    protected function getCityByGeo()
    {
        $code = \CUtil::translit(Manager::getCityName(Manager::getRealIp(), $this->context->getLanguage()), $this->context->getLanguage(), ['replace_space' => '-', 'replace_other' => '-']);
        return $this->getCityList()->getByCode($code);
    }

    /**
     * необходим ли редирект
     * @return bool
     */
    protected function checkRedirect(): bool
    {
        return $this->subDomain !== $this->getCity()->getCode();
    }

    protected function buildUrl(string $cityCode = ''): string
    {
        return 'http' . ($this->context->getRequest()->isHttps() ? 's' : '') . '://'
            . (!empty($cityCode) ? "{$cityCode}." : '')
            . $this->getServerName()
            . $this->context->getServer()->getRequestUri();
    }

    /**
     * возвращает поддомен сайта
     * @return string
     * @throws \Bitrix\Main\SystemException
     */
    protected function getSubDomain(): string
    {
        return Tools::getSubDomain();
    }

    /**
     * возвращает имя сервера
     * @return string
     * @throws \Bitrix\Main\SystemException
     */
    protected function getServerName(): string
    {
        return Tools::getServerName();
    }

    /**
     * флаг нахождение в админке
     * @return bool
     */
    protected function isAdminSection(): bool
    {
        return Tools::isAdminSection();
    }
}