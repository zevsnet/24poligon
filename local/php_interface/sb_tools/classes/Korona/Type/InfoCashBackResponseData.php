<?php

namespace SB\Korona\Type;

class InfoCashBackResponseData
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var \SB\Korona\Type\AvailBnsBalance
     */
    private $availBnsBalance;

    /**
     * @var \SB\Korona\Type\CashBackOperSeq
     */
    private $cashBackOpers;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return InfoCashBackResponseData
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AvailBnsBalance
     */
    public function getAvailBnsBalance()
    {
        return $this->availBnsBalance;
    }

    /**
     * @param \SB\Korona\Type\AvailBnsBalance $availBnsBalance
     * @return InfoCashBackResponseData
     */
    public function withAvailBnsBalance($availBnsBalance)
    {
        $new = clone $this;
        $new->availBnsBalance = $availBnsBalance;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\CashBackOperSeq
     */
    public function getCashBackOpers()
    {
        return $this->cashBackOpers;
    }

    /**
     * @param \SB\Korona\Type\CashBackOperSeq $cashBackOpers
     * @return InfoCashBackResponseData
     */
    public function withCashBackOpers($cashBackOpers)
    {
        $new = clone $this;
        $new->cashBackOpers = $cashBackOpers;

        return $new;
    }


}

