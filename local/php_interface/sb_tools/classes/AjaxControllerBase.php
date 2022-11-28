<?php
namespace SB;

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

    CONST ERROR_NOT_FOUND_METHOD = 1;
    CONST ERROR_VALIDATION = 2;
    CONST ERROR_OTHER = 3;
    CONST ERROR_PARAMS = 4;

    function __construct()
    {
        $this->arResponse['errorStatus'] = false;
        $this->arResponse['arErrors'] = array();
        $this->arResponse['errorText'] = null;
        $this->arResponse['errorCode'] = null;
        $this->arResponse['arResult'] = null;

        $this->mapMethod();
    }

    /**
     * Произвольно преобразует результаты запроса
     *
     * @return mixed
     */
    public function mapResult() { }

    /**
     * Устанавливает соответствия ajax запросов и методов класса
     *
     * @return mixed
     */
    public  function mapMethod() { }

    function run($action)
    {
        if (isset($this->arMapMethod[$action]))
            $methodName = $this->arMapMethod[$action] . 'Method';
        else
            $methodName = $action . 'Method';

        if (!method_exists($this, $methodName))
        {
            $this->addError('Метод не найден', 1);
        }
        else
        {
            try
            {
                $this->$methodName();
            }
            catch(\Exception $Exception)
            {
                $this->addError($Exception->getMessage(), $Exception->getCode());
            }
        }

        $this->mapResult();
    }

    /**
     * Возвращает ответ($this->arResponse) в JSON
     *
     * @return string
     */
    function jsonResponse()
    {
        return json_encode($this->arResponse);
    }

    /**
     * Добавляет ошибку в ответ
     *
     * @param $errorText
     * @param int $errorCode
     */
    public function addError($errorText, $errorCode = 0)
    {
        $this->arResponse['errorStatus'] = true;

        $this->arResponse['errorText'] = $errorText;
        $this->arResponse['errorCode'] = $errorCode;

        $this->arResponse['arErrors'][] = $errorText;
    }

    /**
     * Выбрасывает исключение
     *
     * @param $text
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
        if ($key === false)
            $this->arResponse['arResult'] = $value;
        else
            $this->arResponse['arResult'][$key] = $value;
    }

    public function getResponse()
    {
        return $this->arResponse;
    }

    public function __get($propertyName)
    {
        if ($propertyName=='arResponse')
            return $this->getResponse();
    }

}
