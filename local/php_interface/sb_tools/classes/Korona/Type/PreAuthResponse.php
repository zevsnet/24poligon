<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\ResultInterface;

class PreAuthResponse implements ResultInterface
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var int
     */
    private $pcId;

    /**
     * @var \SB\Korona\Type\PointsAllocation
     */
    private $pointsAlloc;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return PreAuthResponse
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return int
     */
    public function getPcId()
    {
        return $this->pcId;
    }

    /**
     * @param int $pcId
     * @return PreAuthResponse
     */
    public function withPcId($pcId)
    {
        $new = clone $this;
        $new->pcId = $pcId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PointsAllocation
     */
    public function getPointsAlloc()
    {
        return $this->pointsAlloc;
    }

    /**
     * @param \SB\Korona\Type\PointsAllocation $pointsAlloc
     * @return PreAuthResponse
     */
    public function withPointsAlloc($pointsAlloc)
    {
        $new = clone $this;
        $new->pointsAlloc = $pointsAlloc;

        return $new;
    }


}

