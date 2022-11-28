<?php

namespace SB\Korona\Type;

class AuthCashBackOperSeq
{

    /**
     * @var \SB\Korona\Type\AuthCashBackOper
     */
    private $item;

    /**
     * @return \SB\Korona\Type\AuthCashBackOper
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\AuthCashBackOper $item
     * @return AuthCashBackOperSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

