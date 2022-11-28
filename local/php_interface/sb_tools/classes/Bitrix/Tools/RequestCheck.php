<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 20.10.2017
 * Time: 10:03
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Bitrix\Tools;


use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Type\ParameterDictionary;

/**
 * класс для работы с запросом к странице
 * Class RequestCheck
 * @package SB\Bitrix\Tools
 */
class RequestCheck
{
    /** @var HttpRequest запрос */
    protected $request;
    /** @var array данные для проверки */
    protected $data = [];

    /**
     * RequestCheck constructor.
     * @param HttpRequest|null $request
     * @throws \Bitrix\Main\SystemException
     */
    public function __construct(HttpRequest $request = null)
    {
        if($request === null)
        {
            $request = Application::getInstance()->getContext()->getRequest();
        }

        $this->request = $request;
    }

    /**
     * установить проверку на существование данных
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function setIsset(array $arData = array(), string $type = 'get'): self
    {
        $this->setData($arData, $type, 'isset');
        return $this;
    }

    /**
     * добавить данные в проверку существования
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function addIsset(array $arData = array(), string $type = 'get'): self
    {
        $this->addData($arData, $type, 'isset');
        return $this;
    }

    /**
     * установить проверку на несуществование данных
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function setNotIsset(array $arData = array(), string $type = 'get'): self
    {
        $this->setData($arData, $type, '!isset');
        return $this;
    }

    /**
     * добавить данные в проверку несуществования
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function addNotIsset(array $arData = array(), string $type = 'get'): self
    {
        $this->addData($arData, $type, '!isset');
        return $this;
    }

    /**
     * установить проверку на не пустоту данных
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function setEmpty(array $arData = array(), string $type = 'get'): self
    {
        $this->setData($arData, $type, 'empty');
        return $this;
    }

    /**
     * добавить данные в проверку не пустоты
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function addEmpty(array $arData = array(), string $type = 'get'): self
    {
        $this->addData($arData, $type, 'empty');
        return $this;
    }

    /**
     * установить проверку на пустоту данных
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function setNotEmpty(array $arData = array(), string $type = 'get'): self
    {
        $this->setData($arData, $type, '!empty');
        return $this;
    }

    /**
     * добавить данные в проверку пустоты
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function addNotEmpty(array $arData = array(), string $type = 'get'): self
    {
        $this->addData($arData, $type, '!empty');
        return $this;
    }

    /**
     * установить проверку на равенство данных
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function setEqually(array $arData = array(), string $type = 'get'): self
    {
        $this->setData($arData, $type, 'equally');
        return $this;
    }

    /**
     * добавить данные в проверку равенства
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function addEqually(array $arData = array(), string $type = 'get'): self
    {
        $this->addData($arData, $type, 'equally');
        return $this;
    }

    /**
     * установить проверку на не равенство данных
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function setNotEqually(array $arData = array(), string $type = 'get'): self
    {
        $this->setData($arData, $type, '!equally');
        return $this;
    }

    /**
     * добавить данные в проверку не равенства
     * @param array $arData
     * @param string $type - тип данных для проверки (get|post|cookie)
     * @return $this
     */
    public function addNotEqually(array $arData = array(), string $type = 'get'): self
    {
        $this->addData($arData, $type, '!equally');
        return $this;
    }

    /**
     * проверяет ответ на соответсвие фильтров
     * @return bool
     * @throws ArgumentException
     */
    public function check(): bool
    {
        foreach ($this->data as $type => $arMethods)
        {
            /** @var ParameterDictionary $requestData */
            switch ($type)
            {
                case 'get':
                    $requestData = $this->request->getQueryList();
                    break;
                case 'post':
                    $requestData = $this->request->getPostList();
                    break;
                case 'cookie':
                    $requestData = $this->request->getCookieList();
                    break;
                default:
                    throw new ArgumentException('не поддерживаемый параметр ' . $type, $type);
            }

            foreach ($arMethods as $param => $arValue)
            {
                $arValue = (array)$arValue;

                $inverse = strpos($param, '!') === 0;

                $param = $inverse ? substr($param, 1) : $param;

                switch ($param) {
                    case 'isset':
                        foreach ($arValue as $value) {
                            if (
                                (!$requestData->offsetExists($value) && !$inverse) ||
                                ($requestData->offsetExists($value) && $inverse)
                            ) {
                                return false;
                            }
                        }

                        break;
                    case 'empty':
                        foreach ($arValue as $value) {
                            $data = $requestData->get($value);
                            if (
                                (null !== $data && $data !== '' && !$inverse) ||
                                ((null === $data || $data === '') && $inverse)
                            ) {
                                return false;
                            }
                        }

                        break;
                    case 'equally':
                        foreach ($arValue as $valueKey => $value) {
                            $data = $requestData->get($valueKey);
                            if (
                                ($data !== (string)$value && !$inverse) ||
                                ($data === (string)$value && $inverse)
                            ) {
                                return false;
                            }
                        }

                        break;
                    default:
                        throw new ArgumentException('Unknown parameter: ' . $param, $param);

                        break;
                }
            }
        }

        return true;
    }

    /**
     * устанавливет данные для проверки для данного типа и действия
     * @param array $arData
     * @param string $type тип
     * @param string $action действие
     * @throws ArgumentException
     */
    protected function setData(array $arData = array(), string $type = 'get', string $action)
    {
        $this->checkType($type);

        $this->data[$type][$action] = $arData;
    }

    /**
     * добавляет данные для проверки для данного типа и действия
     * @param array $arData
     * @param string $type тип
     * @param string $action действие
     * @throws ArgumentException
     */
    protected function addData(array $arData = array(), string $type = 'get', string $action)
    {
        $this->checkType($type);

        foreach ($arData as $key => $param)
        {
            if(is_numeric($key))
            {
                $this->data[$type][$action][] = $param;
            }
            else
            {
                $this->data[$type][$action][$key] = $param;
            }
        }
    }

    /**
     * проверяет тип (get/post/cookie)
     * @param string $type тип
     * @return bool
     * @throws ArgumentException
     */
    protected function checkType(string $type) : bool
    {
        switch ($type)
        {
            case 'get':
            case 'post':
            case 'cookie':
                return true;
            default:
                throw new ArgumentException('не поддерживаемый тип ' . $type, $type);
        }
    }
}