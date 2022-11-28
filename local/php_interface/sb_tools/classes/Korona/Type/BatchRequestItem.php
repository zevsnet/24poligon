<?php

namespace SB\Korona\Type;

class BatchRequestItem
{

    /**
     * @var \SB\Korona\Type\AuthRequestData
     */
    private $authData;

    /**
     * @var \SB\Korona\Type\RefundRequestData
     */
    private $refundData;

    /**
     * @var \SB\Korona\Type\CardholderRequestData
     */
    private $setCardHolder;

    /**
     * @return \SB\Korona\Type\AuthRequestData
     */
    public function getAuthData()
    {
        return $this->authData;
    }

    /**
     * @param \SB\Korona\Type\AuthRequestData $authData
     * @return BatchRequestItem
     */
    public function withAuthData($authData)
    {
        $new = clone $this;
        $new->authData = $authData;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\RefundRequestData
     */
    public function getRefundData()
    {
        return $this->refundData;
    }

    /**
     * @param \SB\Korona\Type\RefundRequestData $refundData
     * @return BatchRequestItem
     */
    public function withRefundData($refundData)
    {
        $new = clone $this;
        $new->refundData = $refundData;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\CardholderRequestData
     */
    public function getSetCardHolder()
    {
        return $this->setCardHolder;
    }

    /**
     * @param \SB\Korona\Type\CardholderRequestData $setCardHolder
     * @return BatchRequestItem
     */
    public function withSetCardHolder($setCardHolder)
    {
        $new = clone $this;
        $new->setCardHolder = $setCardHolder;

        return $new;
    }


}

