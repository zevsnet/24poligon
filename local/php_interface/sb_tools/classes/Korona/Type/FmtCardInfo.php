<?php

namespace SB\Korona\Type;

class FmtCardInfo
{

    /**
     * @var \SB\Korona\Type\FmtCardInfoItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\FmtCardInfoItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\FmtCardInfoItem $item
     * @return FmtCardInfo
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

