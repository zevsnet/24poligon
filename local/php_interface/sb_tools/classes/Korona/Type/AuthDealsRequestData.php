<?php

namespace SB\Korona\Type;

class AuthDealsRequestData
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\DealSeq
     */
    private $deals;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return AuthDealsRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\DealSeq
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * @param \SB\Korona\Type\DealSeq $deals
     * @return AuthDealsRequestData
     */
    public function withDeals($deals)
    {
        $new = clone $this;
        $new->deals = $deals;

        return $new;
    }


}

