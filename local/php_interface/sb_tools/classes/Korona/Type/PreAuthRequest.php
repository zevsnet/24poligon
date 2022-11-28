<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class PreAuthRequest implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\Cheque
     */
    private $cheque;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var \SB\Korona\Type\Currency
     */
    private $currency;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return PreAuthRequest
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Cheque
     */
    public function getCheque()
    {
        return $this->cheque;
    }

    /**
     * @param \SB\Korona\Type\Cheque $cheque
     * @return PreAuthRequest
     */
    public function withCheque($cheque)
    {
        $new = clone $this;
        $new->cheque = $cheque;

        return $new;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     * @return PreAuthRequest
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param \SB\Korona\Type\Currency $currency
     * @return PreAuthRequest
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }


}

