<?php

namespace SB\Korona\Type;

class ExtParamSeq
{

    /**
     * @var \SB\Korona\Type\ExtParam
     */
    private $item;

    /**
     * @return \SB\Korona\Type\ExtParam
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\ExtParam $item
     * @return ExtParamSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

