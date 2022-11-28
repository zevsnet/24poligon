<?php

namespace SB\Korona\Type;

class RefundRequestData
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\Payment
     */
    private $payment;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @var \SB\Korona\Type\Currency
     */
    private $currency;

    /**
     * @var \SB\Korona\Type\OrigId
     */
    private $origId;

    /**
     * @var \SB\Korona\Type\OrigTerminal
     */
    private $origTerminal;

    /**
     * @var \SB\Korona\Type\OrigLocation
     */
    private $origLocation;

    /**
     * @var \SB\Korona\Type\OrigPartnerId
     */
    private $origPartnerId;

    /**
     * @var \SB\Korona\Type\Cheque
     */
    private $cheque;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return RefundRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param \SB\Korona\Type\Payment $payment
     * @return RefundRequestData
     */
    public function withPayment($payment)
    {
        $new = clone $this;
        $new->payment = $payment;

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
     * @return RefundRequestData
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
     * @return RefundRequestData
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OrigId
     */
    public function getOrigId()
    {
        return $this->origId;
    }

    /**
     * @param \SB\Korona\Type\OrigId $origId
     * @return RefundRequestData
     */
    public function withOrigId($origId)
    {
        $new = clone $this;
        $new->origId = $origId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OrigTerminal
     */
    public function getOrigTerminal()
    {
        return $this->origTerminal;
    }

    /**
     * @param \SB\Korona\Type\OrigTerminal $origTerminal
     * @return RefundRequestData
     */
    public function withOrigTerminal($origTerminal)
    {
        $new = clone $this;
        $new->origTerminal = $origTerminal;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OrigLocation
     */
    public function getOrigLocation()
    {
        return $this->origLocation;
    }

    /**
     * @param \SB\Korona\Type\OrigLocation $origLocation
     * @return RefundRequestData
     */
    public function withOrigLocation($origLocation)
    {
        $new = clone $this;
        $new->origLocation = $origLocation;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OrigPartnerId
     */
    public function getOrigPartnerId()
    {
        return $this->origPartnerId;
    }

    /**
     * @param \SB\Korona\Type\OrigPartnerId $origPartnerId
     * @return RefundRequestData
     */
    public function withOrigPartnerId($origPartnerId)
    {
        $new = clone $this;
        $new->origPartnerId = $origPartnerId;

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
     * @return RefundRequestData
     */
    public function withCheque($cheque)
    {
        $new = clone $this;
        $new->cheque = $cheque;

        return $new;
    }


}

