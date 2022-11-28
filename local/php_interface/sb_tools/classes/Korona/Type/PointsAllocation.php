<?php

namespace SB\Korona\Type;

class PointsAllocation
{

    /**
     * @var \SB\Korona\Type\MinChequePoints
     */
    private $minChequePoints;

    /**
     * @var \SB\Korona\Type\MaxChequePoints
     */
    private $maxChequePoints;

    /**
     * @var \SB\Korona\Type\AllocCheque
     */
    private $allocCheque;

    /**
     * @return \SB\Korona\Type\MinChequePoints
     */
    public function getMinChequePoints()
    {
        return $this->minChequePoints;
    }

    /**
     * @param \SB\Korona\Type\MinChequePoints $minChequePoints
     * @return PointsAllocation
     */
    public function withMinChequePoints($minChequePoints)
    {
        $new = clone $this;
        $new->minChequePoints = $minChequePoints;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\MaxChequePoints
     */
    public function getMaxChequePoints()
    {
        return $this->maxChequePoints;
    }

    /**
     * @param \SB\Korona\Type\MaxChequePoints $maxChequePoints
     * @return PointsAllocation
     */
    public function withMaxChequePoints($maxChequePoints)
    {
        $new = clone $this;
        $new->maxChequePoints = $maxChequePoints;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AllocCheque
     */
    public function getAllocCheque()
    {
        return $this->allocCheque;
    }

    /**
     * @param \SB\Korona\Type\AllocCheque $allocCheque
     * @return PointsAllocation
     */
    public function withAllocCheque($allocCheque)
    {
        $new = clone $this;
        $new->allocCheque = $allocCheque;

        return $new;
    }


}

