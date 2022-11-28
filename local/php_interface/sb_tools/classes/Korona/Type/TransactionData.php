<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class TransactionData implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\Id
     */
    private $id;

    /**
     * @var \SB\Korona\Type\Terminal
     */
    private $terminal;

    /**
     * @var \SB\Korona\Type\Location
     */
    private $location;

    /**
     * @var \SB\Korona\Type\PartnerId
     */
    private $partnerId;

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
     * @var \SB\Korona\Type\Cardholder
     */
    private $cardholder;

    /**
     * @var \SB\Korona\Type\Phone
     */
    private $phone;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var \SB\Korona\Type\ExtensionSeq
     */
    private $extensions;

    /**
     * @return \SB\Korona\Type\Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \SB\Korona\Type\Id $id
     * @return TransactionData
     */
    public function withId($id)
    {
        $new = clone $this;
        $new->id = $id;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Terminal
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @param \SB\Korona\Type\Terminal $terminal
     * @return TransactionData
     */
    public function withTerminal($terminal)
    {
        $new = clone $this;
        $new->terminal = $terminal;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param \SB\Korona\Type\Location $location
     * @return TransactionData
     */
    public function withLocation($location)
    {
        $new = clone $this;
        $new->location = $location;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PartnerId
     */
    public function getPartnerId()
    {
        return $this->partnerId;
    }

    /**
     * @param \SB\Korona\Type\PartnerId $partnerId
     * @return TransactionData
     */
    public function withPartnerId($partnerId)
    {
        $new = clone $this;
        $new->partnerId = $partnerId;

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
     * @return TransactionData
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
     * @return TransactionData
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
     * @return TransactionData
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
     * @return TransactionData
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
     * @return TransactionData
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
     * @return TransactionData
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
     * @return TransactionData
     */
    public function withGcdata($gcdata)
    {
        $new = clone $this;
        $new->gcdata = $gcdata;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Cardholder
     */
    public function getCardholder()
    {
        return $this->cardholder;
    }

    /**
     * @param \SB\Korona\Type\Cardholder $cardholder
     * @return TransactionData
     */
    public function withCardholder($cardholder)
    {
        $new = clone $this;
        $new->cardholder = $cardholder;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param \SB\Korona\Type\Phone $phone
     * @return TransactionData
     */
    public function withPhone($phone)
    {
        $new = clone $this;
        $new->phone = $phone;

        return $new;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return TransactionData
     */
    public function withDateTime($dateTime)
    {
        $new = clone $this;
        $new->dateTime = $dateTime;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ExtensionSeq
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * @param \SB\Korona\Type\ExtensionSeq $extensions
     * @return TransactionData
     */
    public function withExtensions($extensions)
    {
        $new = clone $this;
        $new->extensions = $extensions;

        return $new;
    }


}

