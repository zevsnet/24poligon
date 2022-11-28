<?php

namespace SB\Korona\Type;

class DealTypeSeq
{

    /**
     * @var \SB\Korona\Type\DealType
     */
    private $item;

    /**
     * @return \SB\Korona\Type\DealType
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\DealType $item
     * @return DealTypeSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

