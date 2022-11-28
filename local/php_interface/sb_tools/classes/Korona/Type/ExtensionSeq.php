<?php

namespace SB\Korona\Type;

class ExtensionSeq
{

    /**
     * @var \SB\Korona\Type\Extension
     */
    private $item;

    /**
     * @return \SB\Korona\Type\Extension
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\Extension $item
     * @return ExtensionSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

