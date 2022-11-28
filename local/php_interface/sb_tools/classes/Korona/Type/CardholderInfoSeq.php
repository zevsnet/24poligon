<?php

namespace SB\Korona\Type;

class CardholderInfoSeq
{

    /**
     * @var \SB\Korona\Type\CardholderInfoItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\CardholderInfoItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\CardholderInfoItem $item
     * @return CardholderInfoSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

