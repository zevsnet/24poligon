<?php

namespace SB\Tools;

use Bitrix\Main\Context;
use SB\Result\Ajax;

abstract class AjaxControllerBase
{
    /**
     * массив соответсвия методов ajax и методов класса
     *
     * @var array
     */
    protected $arMapMethod = array();

    /**
     * Массив ответа ajax запроса. Инициализируется в конструкторе
     *
     * @var array
     */
    protected $arResponse = array();
    /**
     * @var array|\Bitrix\Main\HttpRequest
     */
    protected $request = array();

    /**
     * @var Ajax
     */
    protected $result;

    /**
     * если вызывается старым способом
     * @var bool
     */
    protected $old = true;

    CONST ERROR_NOT_FOUND_METHOD = 1;
    CONST ERROR_VALIDATION       = 2;
    CONST ERROR_OTHER            = 3;
    CONST ERROR_PARAMS           = 4;

    function __construct(Ajax $result = null)
    {
        $this->request = Context::getCurrent()->getRequest();

        $this->result = $result;
        if ($this->result === null)
            $this->result = new Ajax('error_status', 'result', 'arErrors');

        //$this->mapMethod();
    }

    /**
     * Произвольно преобразует результаты запроса
     *
     * @return mixed
     */
    public function mapResult()
    {
    }

    /**
     * Устанавливает соответствия ajax запросов и методов класса
     *
     * @return mixed
     */
    public function mapMethod()
    {
    }

    function run($action)
    {
        if (isset($this->arMapMethod[$action]))
            $methodName = $this->arMapMethod[$action] . 'Method';
        else
            $methodName = $action . 'Method';

        if (!method_exists($this, $methodName))
        {
            $this->addError('Metchod not find', 1);
        }
        else
        {
            try
            {
                $this->$methodName();
            }
            catch(\Throwable $Exception)
            {
                $this->addError($Exception->getMessage(), $Exception->getCode());
            }
        }

        $this->arResponse['errorStatus'] = $this->result->status ? false : true;
        $this->arResponse['arErrors'] = &$this->result->errors;
        $this->arResponse['arResult'] = &$this->result->data;

        $this->mapResult();

        if ($this->old)
            $this->result->setStatus($this->arResponse['error_status']);
    }

    /**
     * @return array|\Bitrix\Main\HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * Возвращает ответ($this->arResponse) в JSON
     *
     * @return string
     */
    function jsonResponse()
    {
        return json_encode($this->result->getAnswer());
    }

    /**
     * Добавляет ошибку в ответ
     *
     * @param     $errorText
     * @param int $errorCode
     */
    public function addError($errorText, $errorCode = 0)
    {
        $this->result->addError($errorText, $errorCode);

        $this->result->setStatus(false);
    }

    /**
     * Выбрасывает исключение
     *
     * @param     $text
     * @param int $code
     *
     * @throws \Exception
     */
    public function throwException($text, $code = self::ERROR_OTHER)
    {
        throw new \Exception($text, $code);
    }

    /**
     * Добавляет ошибку валидации в массив $this->arResponse['arResult']['arValidationErrors']
     *
     * @deprecated
     *
     * @param $field - поле формы
     * @param $error - текст ошибки
     */
    public function addValidationError($field, $error)
    {
        $this->arResponse['arResult']['arValidationErrors'][$field] = $error;
    }

    /**
     * Проверяет наличие ошибок валидации в $this->arResponse['arResult']['arValidationErrors']
     *
     * @deprecated
     *
     * @param bool $throwException - флаг выбрасывания исключения в случаи ошибок
     *
     * @return bool
     * @throws \Exception
     */
    public function checkValidationErrors($throwException = true)
    {
        if (!empty($this->arResponse['arResult']['arValidationErrors']))
        {
            if ($throwException)
                throw new \Exception('Ошибка валидации', self::ERROR_VALIDATION);
            else
                return false;
        }

        return true;
    }

    /**
     * Добавляет значение в результат массива
     *
     * @param $key - ключ, если false то $value пишется в $this->arResponse['arResult']
     * @param $value
     */
    public function addResult($key, $value)
    {
        $this->result->addResult($key, $value);
    }

    /**
     * возвращает статус результата
     * @return bool
     */
    public function getStatus()
    {
        return $this->result->getStatus();
    }

    /**
     * получает результат
     * @deprecated
     * @return array
     */
    public function getResponse()
    {
        return $this->arResponse;
    }

    public function __get($propertyName)
    {
        if ($propertyName == 'arResponse')
            return $this->getResponse();
    }

}
