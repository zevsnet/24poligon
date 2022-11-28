<?php

namespace SB\Korona\Type;

class BnsActiveRestrictInfo
{

    /**
     * @var \SB\Korona\Type\Item
     */
    private $item;

    /**
     * @return \SB\Korona\Type\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\Item $item
     * @return BnsActiveRestrictInfo
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

