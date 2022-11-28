<?php

namespace SB\Korona\Type;

class LinkRequestData
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\Track1
     */
    private $track1;

    /**
     * @var \SB\Korona\Type\Track2
     */
    private $track2;

    /**
     * @var \SB\Korona\Type\Track3
     */
    private $track3;

    /**
     * @var \SB\Korona\Type\BarCode
     */
    private $barCode;

    /**
     * @var \SB\Korona\Type\Pan
     */
    private $pan;

    /**
     * @var \SB\Korona\Type\Hash
     */
    private $hash;

    /**
     * @var \SB\Korona\Type\Gcdata
     */
    private $gcdata;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return LinkRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Track1
     */
    public function getTrack1()
    {
        return $this->track1;
    }

    /**
     * @param \SB\Korona\Type\Track1 $track1
     * @return LinkRequestData
     */
    public function withTrack1($track1)
    {
        $new = clone $this;
        $new->track1 = $track1;

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
     * @return LinkRequestData
     */
    public function withTrack2($track2)
    {
        $new = clone $this;
        $new->track2 = $track2;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Track3
     */
    public function getTrack3()
    {
        return $this->track3;
    }

    /**
     * @param \SB\Korona\Type\Track3 $track3
     * @return LinkRequestData
     */
    public function withTrack3($track3)
    {
        $new = clone $this;
        $new->track3 = $track3;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\BarCode
     */
    public function getBarCode()
    {
        return $this->barCode;
    }

    /**
     * @param \SB\Korona\Type\BarCode $barCode
     * @return LinkRequestData
     */
    public function withBarCode($barCode)
    {
        $new = clone $this;
        $new->barCode = $barCode;

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
     * @return LinkRequestData
     */
    public function withPan($pan)
    {
        $new = clone $this;
        $new->pan = $pan;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Hash
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param \SB\Korona\Type\Hash $hash
     * @return LinkRequestData
     */
    public function withHash($hash)
    {
        $new = clone $this;
        $new->hash = $hash;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Gcdata
     */
    public function getGcdata()
    {
        return $this->gcdata;
    }

    /**
     * @param \SB\Korona\Type\Gcdata $gcdata
     * @return LinkRequestData
     */
    public function withGcdata($gcdata)
    {
        $new = clone $this;
        $new->gcdata = $gcdata;

        return $new;
    }


}

