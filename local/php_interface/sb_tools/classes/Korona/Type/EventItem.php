<?php

namespace SB\Korona\Type;

class EventItem
{

    /**
     * @var \SB\Korona\Type\Code
     */
    private $code;

    /**
     * @var \SB\Korona\Type\Message
     */
    private $message;

    /**
     * @return \SB\Korona\Type\Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param \SB\Korona\Type\Code $code
     * @return EventItem
     */
    public function withCode($code)
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \SB\Korona\Type\Message $message
     * @return EventItem
     */
    public function withMessage($message)
    {
        $new = clone $this;
        $new->message = $message;

        return $new;
    }


}

