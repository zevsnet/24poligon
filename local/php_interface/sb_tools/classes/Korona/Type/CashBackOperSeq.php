<?php

namespace SB\Korona\Type;

class CashBackOperSeq
{

    /**
     * @var \SB\Korona\Type\CashBackOper
     */
    private $item;

    /**
     * @return \SB\Korona\Type\CashBackOper
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\CashBackOper $item
     * @return CashBackOperSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

