<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class AuthRequestData implements RequestInterface
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
     * @var \SB\Korona\Type\Currency
     */
    private $currency;

    /**
     * @var \SB\Korona\Type\Payment
     */
    private $payment;

    /**
     * @var \SB\Korona\Type\Cheque
     */
    private $cheque;

    /**
     * @var \SB\Korona\Type\Authentication
     */
    private $authentication;

    /**
     * @var \SB\Korona\Type\AuthCashBackOperSeq
     */
    private $cashBack;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return AuthRequestData
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
     * @return AuthRequestData
     */
    public function withAmount(int $amount)
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
     * @return AuthRequestData
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

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
     * @return AuthRequestData
     */
    public function withPayment($payment)
    {
        $new = clone $this;
        $new->payment = $payment;

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
     * @return AuthRequestData
     */
    public function withCheque($cheque)
    {
        $new = clone $this;
        $new->cheque = $cheque;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Authentication
     */
    public function getAuthentication()
    {
        return $this->authentication;
    }

    /**
     * @param \SB\Korona\Type\Authentication $authentication
     * @return AuthRequestData
     */
    public function withAuthentication($authentication)
    {
        $new = clone $this;
        $new->authentication = $authentication;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AuthCashBackOperSeq
     */
    public function getCashBack()
    {
        return $this->cashBack;
    }

    /**
     * @param \SB\Korona\Type\AuthCashBackOperSeq $cashBack
     * @return AuthRequestData
     */
    public function withCashBack($cashBack)
    {
        $new = clone $this;
        $new->cashBack = $cashBack;

        return $new;
    }


}

