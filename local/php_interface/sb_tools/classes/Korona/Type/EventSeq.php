<?php

namespace SB\Korona\Type;

class EventSeq
{

    /**
     * @var \SB\Korona\Type\EventItem
     */
    private $item;

    /**
     * @return \SB\Korona\Type\EventItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\EventItem $item
     * @return EventSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

