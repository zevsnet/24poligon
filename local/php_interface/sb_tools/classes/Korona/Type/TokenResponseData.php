<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\ResultInterface;

class TokenResponseData implements ResultInterface
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var \SB\Korona\Type\ValidThrough
     */
    private $validThrough;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return TokenResponseData
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ValidThrough
     */
    public function getValidThrough()
    {
        return $this->validThrough;
    }

    /**
     * @param \SB\Korona\Type\ValidThrough $validThrough
     * @return TokenResponseData
     */
    public function withValidThrough($validThrough)
    {
        $new = clone $this;
        $new->validThrough = $validThrough;

        return $new;
    }


}

