<?php

namespace SB\Korona\Type;

class CardInfo
{

    /**
     * @var \SB\Korona\Type\CardInfoItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\CardInfoItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\CardInfoItem $item
     * @return CardInfo
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

