<?php

namespace SB\Korona\Type;

class CashBackOper
{

    /**
     * @var \SB\Korona\Type\PcId
     */
    private $pcId;

    /**
     * @var \SB\Korona\Type\CardNum
     */
    private $cardNum;

    /**
     * @var \SB\Korona\Type\Currency
     */
    private $currency;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @var \SB\Korona\Type\MerchName
     */
    private $merchName;

    /**
     * @var \SB\Korona\Type\Dtime
     */
    private $dtime;

    /**
     * @var \SB\Korona\Type\Rate
     */
    private $rate;

    /**
     * @var \SB\Korona\Type\MinCashBack
     */
    private $minCashBack;

    /**
     * @var \SB\Korona\Type\MaxCashBack
     */
    private $maxCashBack;

    /**
     * @var \SB\Korona\Type\CashBackAmount
     */
    private $cashBackAmount;

    /**
     * @var \SB\Korona\Type\BonusCostAmount
     */
    private $bonusCostAmount;

    /**
     * @return \SB\Korona\Type\PcId
     */
    public function getPcId()
    {
        return $this->pcId;
    }

    /**
     * @param \SB\Korona\Type\PcId $pcId
     * @return CashBackOper
     */
    public function withPcId($pcId)
    {
        $new = clone $this;
        $new->pcId = $pcId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\CardNum
     */
    public function getCardNum()
    {
        return $this->cardNum;
    }

    /**
     * @param \SB\Korona\Type\CardNum $cardNum
     * @return CashBackOper
     */
    public function withCardNum($cardNum)
    {
        $new = clone $this;
        $new->cardNum = $cardNum;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param \SB\Korona\Type\Currency $currency
     * @return CashBackOper
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param \SB\Korona\Type\Amount $amount
     * @return CashBackOper
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\MerchName
     */
    public function getMerchName()
    {
        return $this->merchName;
    }

    /**
     * @param \SB\Korona\Type\MerchName $merchName
     * @return CashBackOper
     */
    public function withMerchName($merchName)
    {
        $new = clone $this;
        $new->merchName = $merchName;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Dtime
     */
    public function getDtime()
    {
        return $this->dtime;
    }

    /**
     * @param \SB\Korona\Type\Dtime $dtime
     * @return CashBackOper
     */
    public function withDtime($dtime)
    {
        $new = clone $this;
        $new->dtime = $dtime;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Rate
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param \SB\Korona\Type\Rate $rate
     * @return CashBackOper
     */
    public function withRate($rate)
    {
        $new = clone $this;
        $new->rate = $rate;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\MinCashBack
     */
    public function getMinCashBack()
    {
        return $this->minCashBack;
    }

    /**
     * @param \SB\Korona\Type\MinCashBack $minCashBack
     * @return CashBackOper
     */
    public function withMinCashBack($minCashBack)
    {
        $new = clone $this;
        $new->minCashBack = $minCashBack;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\MaxCashBack
     */
    public function getMaxCashBack()
    {
        return $this->maxCashBack;
    }

    /**
     * @param \SB\Korona\Type\MaxCashBack $maxCashBack
     * @return CashBackOper
     */
    public function withMaxCashBack($maxCashBack)
    {
        $new = clone $this;
        $new->maxCashBack = $maxCashBack;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\CashBackAmount
     */
    public function getCashBackAmount()
    {
        return $this->cashBackAmount;
    }

    /**
     * @param \SB\Korona\Type\CashBackAmount $cashBackAmount
     * @return CashBackOper
     */
    public function withCashBackAmount($cashBackAmount)
    {
        $new = clone $this;
        $new->cashBackAmount = $cashBackAmount;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\BonusCostAmount
     */
    public function getBonusCostAmount()
    {
        return $this->bonusCostAmount;
    }

    /**
     * @param \SB\Korona\Type\BonusCostAmount $bonusCostAmount
     * @return CashBackOper
     */
    public function withBonusCostAmount($bonusCostAmount)
    {
        $new = clone $this;
        $new->bonusCostAmount = $bonusCostAmount;

        return $new;
    }


}

