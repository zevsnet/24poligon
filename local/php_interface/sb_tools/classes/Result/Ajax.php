<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 07.09.2017
 * Time: 14:48
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Result;

class Ajax
{
    protected $keys = array();

    /** @var bool */
    public $status = true;
    public $data = array();
    public $errors = array();

    public function __construct($status = 'status', $data = 'data', $error = 'error')
    {
        $this->keys = array(
            'status' => $status,
            'data'   => $data,
            'error'  => $error
        );
    }

    /**
     * @param string $key
     */
    public function setStatusKey($key)
    {
        $this->keys['status'] = $key;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getKey($key)
    {
        return $this->keys[$key];
    }

    /**
     * @param string $key
     */
    public function setDataKey($key)
    {
        $this->keys['data'] = $key;
    }

    /**
     * @param string $key
     */
    public function setErrorKey($key)
    {
        $this->keys['error'] = $key;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function setStatus($status = true)
    {
        $this->status = $status;

        return $this;
    }


    /**
     * @param string $string
     * @param int    $code
     *
     * @return $this
     */
    public function addError($string, $code = 0)
    {
        $this->errors[] = array(
            'text' => $string,
            'code' => $code
        );

        $this->setStatus(false);

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function addResult($key, $value)
    {
        if ($key === false)
            $this->data = $value;
        else
            $this->data[$key] = $value;

        return $this;
    }


    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }


    /**
     * @return array
     */
    public function getAnswer()
    {
        return array(
            $this->getKey('status') => $this->getStatus(),
            $this->getKey('data') => $this->getData(),
            $this->getKey('error') => $this->getErrors()
        );
    }
}