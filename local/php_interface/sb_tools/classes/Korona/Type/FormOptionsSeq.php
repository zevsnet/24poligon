<?php

namespace SB\Korona\Type;

class FormOptionsSeq
{

    /**
     * @var \SB\Korona\Type\FormOptionsItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\FormOptionsItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\FormOptionsItem $item
     * @return FormOptionsSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

