<?php

namespace SB\Korona\Type;

class IssueDealResponseData
{

    /**
     * @var \SB\Korona\Type\AuthResponseData
     */
    private $authRes;

    /**
     * @var \SB\Korona\Type\Deal
     */
    private $deal;

    /**
     * @return \SB\Korona\Type\AuthResponseData
     */
    public function getAuthRes()
    {
        return $this->authRes;
    }

    /**
     * @param \SB\Korona\Type\AuthResponseData $authRes
     * @return IssueDealResponseData
     */
    public function withAuthRes($authRes)
    {
        $new = clone $this;
        $new->authRes = $authRes;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Deal
     */
    public function getDeal()
    {
        return $this->deal;
    }

    /**
     * @param \SB\Korona\Type\Deal $deal
     * @return IssueDealResponseData
     */
    public function withDeal($deal)
    {
        $new = clone $this;
        $new->deal = $deal;

        return $new;
    }


}

