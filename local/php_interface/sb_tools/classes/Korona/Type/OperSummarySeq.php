<?php

namespace SB\Korona\Type;

class OperSummarySeq
{

    /**
     * @var \SB\Korona\Type\OperSummaryItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\OperSummaryItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\OperSummaryItem $item
     * @return OperSummarySeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

