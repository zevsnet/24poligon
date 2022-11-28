<?php

namespace SB\Korona\Type;

class BatchRequestData
{

    /**
     * @var \SB\Korona\Type\BatchRequestSequence
     */
    private $sequence;

    /**
     * @return \SB\Korona\Type\BatchRequestSequence
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param \SB\Korona\Type\BatchRequestSequence $sequence
     * @return BatchRequestData
     */
    public function withSequence($sequence)
    {
        $new = clone $this;
        $new->sequence = $sequence;

        return $new;
    }


}

