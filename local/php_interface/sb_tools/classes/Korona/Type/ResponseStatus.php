<?php

namespace SB\Korona\Type;

class ResponseStatus
{

    /**
     * @var \SB\Korona\Type\Code
     */
    private $code;

    /**
     * @var \SB\Korona\Type\Description
     */
    private $description;

    /**
     * @return \SB\Korona\Type\Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param \SB\Korona\Type\Code $code
     * @return ResponseStatus
     */
    public function withCode($code)
    {
        $new = clone $this;
        $new->code = $code;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param \SB\Korona\Type\Description $description
     * @return ResponseStatus
     */
    public function withDescription($description)
    {
        $new = clone $this;
        $new->description = $description;

        return $new;
    }


}

