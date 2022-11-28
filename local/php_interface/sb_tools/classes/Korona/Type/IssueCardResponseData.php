<?php

namespace SB\Korona\Type;

class IssueCardResponseData
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var \SB\Korona\Type\Pan
     */
    private $pan;

    /**
     * @var \SB\Korona\Type\Track2
     */
    private $track2;

    /**
     * @var \SB\Korona\Type\Cvc
     */
    private $cvc;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return IssueCardResponseData
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Pan
     */
    public function getPan()
    {
        return $this->pan;
    }

    /**
     * @param \SB\Korona\Type\Pan $pan
     * @return IssueCardResponseData
     */
    public function withPan($pan)
    {
        $new = clone $this;
        $new->pan = $pan;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Track2
     */
    public function getTrack2()
    {
        return $this->track2;
    }

    /**
     * @param \SB\Korona\Type\Track2 $track2
     * @return IssueCardResponseData
     */
    public function withTrack2($track2)
    {
        $new = clone $this;
        $new->track2 = $track2;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Cvc
     */
    public function getCvc()
    {
        return $this->cvc;
    }

    /**
     * @param \SB\Korona\Type\Cvc $cvc
     * @return IssueCardResponseData
     */
    public function withCvc($cvc)
    {
        $new = clone $this;
        $new->cvc = $cvc;

        return $new;
    }


}

