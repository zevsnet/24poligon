<?php

namespace SB\Model\Ajax;

/**
 * класс для создания ответов
 * Class Response
 * @package SB\Model\Ajax
 */
class Response implements \JsonSerializable
{
    /** @var string ключ статуса */
    protected $statusKey = 'status';
    /** @var bool статус */
    protected $status = true;

    /** @var string ключ данных */
    protected $dataKey = 'data';
    /** @var array данные */
    protected $data = [];

    /** @var string ключ ошибок */
    protected $errorKey = 'error';
    /** @var Error[] ошибки */
    protected $error = [];

    /**
     * Response constructor.
     * @param $status
     * @param $data
     * @param $error
     */
    public function __construct($status = 'status', $data = 'data', $error = 'error')
    {
        $this->statusKey = $status;
        $this->dataKey = $data;
        $this->errorKey = $error;
    }

    /**
     * установки статуса ответа
     * @param bool $status
     * @return Response
     */
    public function setStatus(bool $status): Response
    {
        $this->status = $status;
        return $this;
    }

    /**
     * добавление ошибки в результат
     * @param $string
     * @param int $code
     * @return Response
     */
    public function addError($string, $code = 0): Response
    {
        $this->error[]= new Error($string, $code);
        $this->setStatus(false);
        return $this;
    }

    /**
     * добавление данных в результат
     * @param string $key
     * @param mixed $value
     * @return Response
     */
    public function addData($key, $value): Response
    {
        if ($key === false) {
            $this->data = $value;
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    /**
     * возвращает статус ответа
     * @return bool
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * возвращает результат
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * возращает массив ошибок
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->error;
    }

    /**
     * Отдаёт результат при сериализации
     * @return array|mixed,
     */
    public function jsonSerialize()
    {
        return [
            $this->statusKey => $this->status,
            $this->dataKey => $this->data,
            $this->errorKey => $this->error
        ];
    }
}