<?php

namespace SB\Korona\Type;

class AccStatementSeq
{

    /**
     * @var \SB\Korona\Type\AccStatementItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\AccStatementItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\AccStatementItem $item
     * @return AccStatementSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

