<?php

namespace SB\Korona\Type;

class DiscountRuleSeq
{

    /**
     * @var \SB\Korona\Type\DiscountRule
     */
    private $item;

    /**
     * @return \SB\Korona\Type\DiscountRule
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\DiscountRule $item
     * @return DiscountRuleSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

