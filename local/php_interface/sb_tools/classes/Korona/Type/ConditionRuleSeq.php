<?php

namespace SB\Korona\Type;

class ConditionRuleSeq
{

    /**
     * @var \SB\Korona\Type\ConditionRule
     */
    private $item;

    /**
     * @return \SB\Korona\Type\ConditionRule
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param \SB\Korona\Type\ConditionRule $item
     * @return ConditionRuleSeq
     */
    public function withItem($item)
    {
        $new = clone $this;
        $new->item = $item;

        return $new;
    }


}

