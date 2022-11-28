<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Payment implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\PaymentItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\PaymentItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\PaymentItem $item
     * @return Payment
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

