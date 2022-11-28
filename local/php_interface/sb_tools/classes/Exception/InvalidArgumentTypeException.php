<?php
/**
 * Created by PhpStorm.
 * User: dnkolosov
 * Date: 15.03.2018
 * Time: 10:44
 * @author Denis Kolosov <kdnn@mail.ru>
 */

namespace SB\Exception;


use SB\Exception;

class InvalidArgumentTypeException extends Exception
{
    /** @var string тип аргумента */
    protected $requiredType;

    /**
     * Creates new exception object
     *
     * @param string $parameter Argument that generates exception
     * @param string $requiredType Required type
     * @param \Exception $previous
     */
    public function __construct($parameter, string $requiredType = '', \Exception $previous = null)
    {
        if (!empty($requiredType)) {
            $message = sprintf("Значение аргумента '%s' должно быть типа %s", $parameter, $requiredType);
        }
        else {
            $message = sprintf("Значение аргумента '%s' недопустимого типа", $parameter);
        }

        $this->requiredType = $requiredType;

        parent::__construct($message, 100, $previous);
    }

    public function getRequiredType(): string
    {
        return $this->requiredType;
    }
}