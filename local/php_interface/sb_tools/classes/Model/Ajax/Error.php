<?php

namespace SB\Model\Ajax;

class Error implements \JsonSerializable
{
    protected $message;
    protected $code;

    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    /**
     * функция отрабатывающая при сереалайзе
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'message' => $this->message,
            'code' => $this->code
        ];
    }
}