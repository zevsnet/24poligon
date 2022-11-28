<?php

namespace SB\Korona\Type;

class DealSeq
{

    /**
     * @var \SB\Korona\Type\Deal
     */
    private $item;

    /**
     * @return \SB\Korona\Type\Deal
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\Deal $item
     * @return DealSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

