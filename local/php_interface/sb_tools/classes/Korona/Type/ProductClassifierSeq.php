<?php

namespace SB\Korona\Type;

class ProductClassifierSeq
{

    /**
     * @var \SB\Korona\Type\ProductClassifier
     */
    private $item;

    /**
     * @return \SB\Korona\Type\ProductClassifier
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\ProductClassifier $item
     * @return ProductClassifierSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

