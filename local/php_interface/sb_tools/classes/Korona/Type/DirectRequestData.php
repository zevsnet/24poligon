<?php

namespace SB\Korona\Type;

class DirectRequestData
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @var \SB\Korona\Type\PromoId
     */
    private $promoId;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return DirectRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

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
     * @return DirectRequestData
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PromoId
     */
    public function getPromoId()
    {
        return $this->promoId;
    }

    /**
     * @param \SB\Korona\Type\PromoId $promoId
     * @return DirectRequestData
     */
    public function withPromoId($promoId)
    {
        $new = clone $this;
        $new->promoId = $promoId;

        return $new;
    }


}

