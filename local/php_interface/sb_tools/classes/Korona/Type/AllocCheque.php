<?php

namespace SB\Korona\Type;

class AllocCheque
{

    /**
     * @var \SB\Korona\Type\AllocChequeItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\AllocChequeItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\AllocChequeItem $item
     * @return AllocCheque
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

