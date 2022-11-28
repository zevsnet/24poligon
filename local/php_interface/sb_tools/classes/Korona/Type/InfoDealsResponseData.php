<?php

namespace SB\Korona\Type;

class InfoDealsResponseData
{

    /**
     * @var \SB\Korona\Type\AuthResponseData
     */
    private $authRes;

    /**
     * @var \SB\Korona\Type\DealSeq
     */
    private $deals;

    /**
     * @var \SB\Korona\Type\DealTypeSeq
     */
    private $dealTypes;

    /**
     * @return \SB\Korona\Type\AuthResponseData
     */
    public function getAuthRes()
    {
        return $this->authRes;
    }

    /**
     * @param \SB\Korona\Type\AuthResponseData $authRes
     * @return InfoDealsResponseData
     */
    public function withAuthRes($authRes)
    {
        $new = clone $this;
        $new->authRes = $authRes;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\DealSeq
     */
    public function getDeals()
    {
        return $this->deals;
    }

    /**
     * @param \SB\Korona\Type\DealSeq $deals
     * @return InfoDealsResponseData
     */
    public function withDeals($deals)
    {
        $new = clone $this;
        $new->deals = $deals;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\DealTypeSeq
     */
    public function getDealTypes()
    {
        return $this->dealTypes;
    }

    /**
     * @param \SB\Korona\Type\DealTypeSeq $dealTypes
     * @return InfoDealsResponseData
     */
    public function withDealTypes($dealTypes)
    {
        $new = clone $this;
        $new->dealTypes = $dealTypes;

        return $new;
    }


}

