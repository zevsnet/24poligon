<?php

namespace SB\Korona\Type;

class BatchRequestSequence
{

    /**
     * @var \SB\Korona\Type\BatchRequestItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\BatchRequestItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\BatchRequestItem $item
     * @return BatchRequestSequence
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

