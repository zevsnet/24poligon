<?php

namespace SB\Korona\Type;

class AccStatementParams
{

    /**
     * @var \SB\Korona\Type\CountLimit
     */
    private $countLimit;

    /**
     * @return \SB\Korona\Type\CountLimit
     */
    public function getCountLimit()
    {
        return $this->countLimit;
    }

    /**
     * @param \SB\Korona\Type\CountLimit $countLimit
     * @return AccStatementParams
     */
    public function withCountLimit($countLimit)
    {
        $new = clone $this;
        $new->countLimit = $countLimit;

        return $new;
    }


}

