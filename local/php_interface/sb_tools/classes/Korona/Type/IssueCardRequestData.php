<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class IssueCardRequestData implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\TransactionId
     */
    private $transactionId;

    /**
     * @var \SB\Korona\Type\Terminal
     */
    private $terminal;

    /**
     * @var \SB\Korona\Type\Location
     */
    private $location;

    /**
     * @var \SB\Korona\Type\PartnerId
     */
    private $partnerId;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var \SB\Korona\Type\PbOnlineIdv
     */
    private $pbOnlineIdv;

    /**
     * @return \SB\Korona\Type\TransactionId
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param \SB\Korona\Type\TransactionId $transactionId
     * @return IssueCardRequestData
     */
    public function withTransactionId($transactionId)
    {
        $new = clone $this;
        $new->transactionId = $transactionId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Terminal
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @param \SB\Korona\Type\Terminal $terminal
     * @return IssueCardRequestData
     */
    public function withTerminal($terminal)
    {
        $new = clone $this;
        $new->terminal = $terminal;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \SB\Korona\Type\Location $location
     * @return IssueCardRequestData
     */
    public function withLocation($location)
    {
        $new = clone $this;
        $new->location = $location;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PartnerId
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * @param \SB\Korona\Type\PartnerId $partnerId
     * @return IssueCardRequestData
     */
    public function withPartnerId($partnerId)
    {
        $new = clone $this;
        $new->partnerId = $partnerId;

        return $new;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return IssueCardRequestData
     */
    public function withDateTime($dateTime)
    {
        $new = clone $this;
        $new->dateTime = $dateTime;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PbOnlineIdv
     */
    public function getPbOnlineIdv()
    {
        return $this->pbOnlineIdv;
    }

    /**
     * @param \SB\Korona\Type\PbOnlineIdv $pbOnlineIdv
     * @return IssueCardRequestData
     */
    public function withPbOnlineIdv($pbOnlineIdv)
    {
        $new = clone $this;
        $new->pbOnlineIdv = $pbOnlineIdv;

        return $new;
    }


}

