<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class PaymentItem implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\PayMeans
     */
    private $payMeans;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @return \SB\Korona\Type\PayMeans
     */
    public function getPayMeans()
    {
        return $this->payMeans;
    }

    /**
     * @param \SB\Korona\Type\PayMeans $payMeans
     * @return PaymentItem
     */
    public function withPayMeans($payMeans)
    {
        $new = clone $this;
        $new->payMeans = $payMeans;

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
     * @return PaymentItem
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }


}

