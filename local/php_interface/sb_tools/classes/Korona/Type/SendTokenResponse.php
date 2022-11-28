<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\ResultInterface;

class SendTokenResponse implements ResultInterface
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var \SB\Korona\Type\Token
     */
    private $token;

    /**
     * @var \SB\Korona\Type\ValidThru
     */
    private $validThru;

    /**
     * @var \SB\Korona\Type\Integer
     */
    private $availableTries;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return SendTokenResponse
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param \SB\Korona\Type\Token $token
     * @return SendTokenResponse
     */
    public function withToken($token)
    {
        $new = clone $this;
        $new->token = $token;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ValidThru
     */
    public function getValidThru()
    {
        return $this->validThru;
    }

    /**
     * @param \SB\Korona\Type\ValidThru $validThru
     * @return SendTokenResponse
     */
    public function withValidThru($validThru)
    {
        $new = clone $this;
        $new->validThru = $validThru;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Integer
     */
    public function getAvailableTries()
    {
        return $this->availableTries;
    }

    /**
     * @param \SB\Korona\Type\Integer $availableTries
     * @return SendTokenResponse
     */
    public function withAvailableTries($availableTries)
    {
        $new = clone $this;
        $new->availableTries = $availableTries;

        return $new;
    }


}

