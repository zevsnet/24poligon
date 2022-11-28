<?php

namespace SB\Korona\Type;

class IssueDealRequestData
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\IssuedDeal
     */
    private $deal;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return IssueDealRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\IssuedDeal
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * @param \SB\Korona\Type\IssuedDeal $deal
     * @return IssueDealRequestData
     */
    public function withDeal($deal)
    {
        $new = clone $this;
        $new->deal = $deal;

        return $new;
    }


}

