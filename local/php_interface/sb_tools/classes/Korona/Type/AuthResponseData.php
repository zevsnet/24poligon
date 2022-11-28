<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\ResultInterface;

class AuthResponseData implements ResultInterface
{

    /**
     * @var \SB\Korona\Type\ResponseStatus
     */
    private $status;

    /**
     * @var \SB\Korona\Type\PcId
     */
    private $pcId;

    /**
     * @var \SB\Korona\Type\CardInfo
     */
    private $cardInfo;

    /**
     * @var \SB\Korona\Type\FmtCardInfo
     */
    private $fmtCardInfo;

    /**
     * @var \SB\Korona\Type\ChequeMessage
     */
    private $chequeMessage;

    /**
     * @var \SB\Korona\Type\OperatorMessage
     */
    private $operatorMessage;

    /**
     * @var \SB\Korona\Type\PointsAllocation
     */
    private $pointsAllocation;

    /**
     * @var \SB\Korona\Type\CardholderInfoSeq
     */
    private $cardholderInfo;

    /**
     * @var \SB\Korona\Type\AccStatementSeq
     */
    private $accStatementInfo;

    /**
     * @var \SB\Korona\Type\EventSeq
     */
    private $eventInfo;

    /**
     * @var \SB\Korona\Type\PreCalculatedBns
     */
    private $preCalculatedBns;

    /**
     * @var \SB\Korona\Type\BnsActiveRestrictInfo
     */
    private $bnsActiveRestrictInfo;

    /**
     * @return \SB\Korona\Type\ResponseStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param \SB\Korona\Type\ResponseStatus $status
     * @return AuthResponseData
     */
    public function withStatus($status)
    {
        $new = clone $this;
        $new->status = $status;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PcId
     */
    public function getPcId()
    {
        return $this->pcId;
    }

    /**
     * @param \SB\Korona\Type\PcId $pcId
     * @return AuthResponseData
     */
    public function withPcId($pcId)
    {
        $new = clone $this;
        $new->pcId = $pcId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\CardInfo
     */
    public function getCardInfo()
    {
        return $this->cardInfo;
    }

    /**
     * @param \SB\Korona\Type\CardInfo $cardInfo
     * @return AuthResponseData
     */
    public function withCardInfo($cardInfo)
    {
        $new = clone $this;
        $new->cardInfo = $cardInfo;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\FmtCardInfo
     */
    public function getFmtCardInfo()
    {
        return $this->fmtCardInfo;
    }

    /**
     * @param \SB\Korona\Type\FmtCardInfo $fmtCardInfo
     * @return AuthResponseData
     */
    public function withFmtCardInfo($fmtCardInfo)
    {
        $new = clone $this;
        $new->fmtCardInfo = $fmtCardInfo;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ChequeMessage
     */
    public function getChequeMessage()
    {
        return $this->chequeMessage;
    }

    /**
     * @param \SB\Korona\Type\ChequeMessage $chequeMessage
     * @return AuthResponseData
     */
    public function withChequeMessage($chequeMessage)
    {
        $new = clone $this;
        $new->chequeMessage = $chequeMessage;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\OperatorMessage
     */
    public function getOperatorMessage()
    {
        return $this->operatorMessage;
    }

    /**
     * @param \SB\Korona\Type\OperatorMessage $operatorMessage
     * @return AuthResponseData
     */
    public function withOperatorMessage($operatorMessage)
    {
        $new = clone $this;
        $new->operatorMessage = $operatorMessage;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PointsAllocation
     */
    public function getPointsAllocation()
    {
        return $this->pointsAllocation;
    }

    /**
     * @param \SB\Korona\Type\PointsAllocation $pointsAllocation
     * @return AuthResponseData
     */
    public function withPointsAllocation($pointsAllocation)
    {
        $new = clone $this;
        $new->pointsAllocation = $pointsAllocation;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\CardholderInfoSeq
     */
    public function getCardholderInfo()
    {
        return $this->cardholderInfo;
    }

    /**
     * @param \SB\Korona\Type\CardholderInfoSeq $cardholderInfo
     * @return AuthResponseData
     */
    public function withCardholderInfo($cardholderInfo)
    {
        $new = clone $this;
        $new->cardholderInfo = $cardholderInfo;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AccStatementSeq
     */
    public function getAccStatementInfo()
    {
        return $this->accStatementInfo;
    }

    /**
     * @param \SB\Korona\Type\AccStatementSeq $accStatementInfo
     * @return AuthResponseData
     */
    public function withAccStatementInfo($accStatementInfo)
    {
        $new = clone $this;
        $new->accStatementInfo = $accStatementInfo;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\EventSeq
     */
    public function getEventInfo()
    {
        return $this->eventInfo;
    }

    /**
     * @param \SB\Korona\Type\EventSeq $eventInfo
     * @return AuthResponseData
     */
    public function withEventInfo($eventInfo)
    {
        $new = clone $this;
        $new->eventInfo = $eventInfo;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PreCalculatedBns
     */
    public function getPreCalculatedBns()
    {
        return $this->preCalculatedBns;
    }

    /**
     * @param \SB\Korona\Type\PreCalculatedBns $preCalculatedBns
     * @return AuthResponseData
     */
    public function withPreCalculatedBns($preCalculatedBns)
    {
        $new = clone $this;
        $new->preCalculatedBns = $preCalculatedBns;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\BnsActiveRestrictInfo
     */
    public function getBnsActiveRestrictInfo()
    {
        return $this->bnsActiveRestrictInfo;
    }

    /**
     * @param \SB\Korona\Type\BnsActiveRestrictInfo $bnsActiveRestrictInfo
     * @return AuthResponseData
     */
    public function withBnsActiveRestrictInfo($bnsActiveRestrictInfo)
    {
        $new = clone $this;
        $new->bnsActiveRestrictInfo = $bnsActiveRestrictInfo;

        return $new;
    }


}

