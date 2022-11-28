<?php

namespace SB\Korona\Type;

class RefundResponseData
{

    /**
     * @var \SB\Korona\Type\AuthResponseData
     */
    private $authRes;

    /**
     * @var \SB\Korona\Type\Payment
     */
    private $payment;

    /**
     * @return \SB\Korona\Type\AuthResponseData
     */
    public function getAuthRes()
    {
        return $this->authRes;
    }

    /**
     * @param \SB\Korona\Type\AuthResponseData $authRes
     * @return RefundResponseData
     */
    public function withAuthRes($authRes)
    {
        $new = clone $this;
        $new->authRes = $authRes;

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
     * @return RefundResponseData
     */
    public function withPayment($payment)
    {
        $new = clone $this;
        $new->payment = $payment;

        return $new;
    }


}

