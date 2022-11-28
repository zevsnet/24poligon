<?php

namespace SB\Korona\Type;

class AccStatementItem
{

    /**
     * @var \SB\Korona\Type\PcId
     */
    private $pcId;

    /**
     * @var \SB\Korona\Type\OperKind
     */
    private $operKind;

    /**
     * @var \SB\Korona\Type\OperDesc
     */
    private $operDesc;

    /**
     * @var \SB\Korona\Type\PcDateTime
     */
    private $pcDateTime;

    /**
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @var \SB\Korona\Type\PartnerName
     */
    private $partnerName;

    /**
     * @var \SB\Korona\Type\OperSummarySeq
     */
    private $summary;

    /**
     * @return \SB\Korona\Type\PcId
     */
    public function getPcId()
    {
        return $this->pcId;
    }

    /**
     * @param \SB\Korona\Type\PcId $pcId
     * @return AccStatementItem
     */
    public function withPcId($pcId)
    {
        $new = clone $this;
        $new->pcId = $pcId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OperKind
     */
    public function getOperKind()
    {
        return $this->operKind;
    }

    /**
     * @param \SB\Korona\Type\OperKind $operKind
     * @return AccStatementItem
     */
    public function withOperKind($operKind)
    {
        $new = clone $this;
        $new->operKind = $operKind;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OperDesc
     */
    public function getOperDesc()
    {
        return $this->operDesc;
    }

    /**
     * @param \SB\Korona\Type\OperDesc $operDesc
     * @return AccStatementItem
     */
    public function withOperDesc($operDesc)
    {
        $new = clone $this;
        $new->operDesc = $operDesc;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PcDateTime
     */
    public function getPcDateTime()
    {
        return $this->pcDateTime;
    }

    /**
     * @param \SB\Korona\Type\PcDateTime $pcDateTime
     * @return AccStatementItem
     */
    public function withPcDateTime($pcDateTime)
    {
        $new = clone $this;
        $new->pcDateTime = $pcDateTime;

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
     * @return AccStatementItem
     */
    public function withDateTime($dateTime)
    {
        $new = clone $this;
        $new->dateTime = $dateTime;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PartnerName
     */
    public function getPartnerName()
    {
        return $this->partnerName;
    }

    /**
     * @param \SB\Korona\Type\PartnerName $partnerName
     * @return AccStatementItem
     */
    public function withPartnerName($partnerName)
    {
        $new = clone $this;
        $new->partnerName = $partnerName;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OperSummarySeq
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param \SB\Korona\Type\OperSummarySeq $summary
     * @return AccStatementItem
     */
    public function withSummary($summary)
    {
        $new = clone $this;
        $new->summary = $summary;

        return $new;
    }


}

