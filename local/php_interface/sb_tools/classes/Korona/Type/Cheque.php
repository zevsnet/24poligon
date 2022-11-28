<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Cheque
{

    /**
     * @var \SB\Korona\Type\ChequeItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\ChequeItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\ChequeItem $item
     * @return Cheque
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

