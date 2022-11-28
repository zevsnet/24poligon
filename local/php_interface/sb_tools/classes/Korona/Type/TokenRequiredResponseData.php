<?php

namespace SB\Korona\Type;

class TokenRequiredResponseData
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var bool
     */
    private $tokenRequired;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return TokenRequiredResponseData
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return bool
     */
    public function getTokenRequired()
    {
        return $this->tokenRequired;
    }

    /**
     * @param bool $tokenRequired
     * @return TokenRequiredResponseData
     */
    public function withTokenRequired($tokenRequired)
    {
        $new = clone $this;
        $new->tokenRequired = $tokenRequired;

        return $new;
    }


}

