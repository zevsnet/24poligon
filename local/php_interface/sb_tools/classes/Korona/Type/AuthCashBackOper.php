<?php

namespace SB\Korona\Type;

class AuthCashBackOper
{

    /**
     * @var \SB\Korona\Type\PcId
     */
    private $pcId;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @return \SB\Korona\Type\PcId
     */
    public function getPcId()
    {
        return $this->pcId;
    }

    /**
     * @param \SB\Korona\Type\PcId $pcId
     * @return AuthCashBackOper
     */
    public function withPcId($pcId)
    {
        $new = clone $this;
        $new->pcId = $pcId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param \SB\Korona\Type\Amount $amount
     * @return AuthCashBackOper
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }


}

