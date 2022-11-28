<?php

namespace SB\Korona\Type;

class ChequeItemAttrSeq
{

    /**
     * @var \SB\Korona\Type\ChequeItemAttr
     */
    private $item;

    /**
     * @return \SB\Korona\Type\ChequeItemAttr
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\ChequeItemAttr $item
     * @return ChequeItemAttrSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

