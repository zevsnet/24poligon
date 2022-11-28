<?php

namespace SB\Korona\Type;

use Phpro\SoapClient\Type\RequestInterface;

class InfoRequestData implements RequestInterface
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\Cheque
     */
    private $cheque;

    /**
     * @var \SB\Korona\Type\GetCardholder
     */
    private $getCardholder;

    /**
     * @var \SB\Korona\Type\GetAccStatement
     */
    private $getAccStatement;

    /**
     * @var \SB\Korona\Type\AccStatementParams
     */
    private $accStatementParams;

    /**
     * @var \SB\Korona\Type\GetPreCalcBns
     */
    private $getPreCalcBns;

    /**
     * @var \SB\Korona\Type\Currency
     */
    private $currency;

    /**
     * @var \SB\Korona\Type\GetBnsActiveRestrictInfo
     */
    private $getBnsActiveRestrictInfo;

    /**
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return InfoRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Cheque
     */
    public function getCheque()
    {
        return $this->cheque;
    }

    /**
     * @param \SB\Korona\Type\Cheque $cheque
     * @return InfoRequestData
     */
    public function withCheque($cheque)
    {
        $new = clone $this;
        $new->cheque = $cheque;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\GetCardholder
     */
    public function getGetCardholder()
    {
        return $this->getCardholder;
    }

    /**
     * @param \SB\Korona\Type\GetCardholder $getCardholder
     * @return InfoRequestData
     */
    public function withGetCardholder($getCardholder)
    {
        $new = clone $this;
        $new->getCardholder = $getCardholder;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\GetAccStatement
     */
    public function getGetAccStatement()
    {
        return $this->getAccStatement;
    }

    /**
     * @param \SB\Korona\Type\GetAccStatement $getAccStatement
     * @return InfoRequestData
     */
    public function withGetAccStatement($getAccStatement)
    {
        $new = clone $this;
        $new->getAccStatement = $getAccStatement;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AccStatementParams
     */
    public function getAccStatementParams()
    {
        return $this->accStatementParams;
    }

    /**
     * @param \SB\Korona\Type\AccStatementParams $accStatementParams
     * @return InfoRequestData
     */
    public function withAccStatementParams($accStatementParams)
    {
        $new = clone $this;
        $new->accStatementParams = $accStatementParams;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\GetPreCalcBns
     */
    public function getGetPreCalcBns()
    {
        return $this->getPreCalcBns;
    }

    /**
     * @param \SB\Korona\Type\GetPreCalcBns $getPreCalcBns
     * @return InfoRequestData
     */
    public function withGetPreCalcBns($getPreCalcBns)
    {
        $new = clone $this;
        $new->getPreCalcBns = $getPreCalcBns;

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
     * @param string $currency
     * @return InfoRequestData
     */
    public function withCurrency($currency)
    {
        $new = clone $this;
        $new->currency = $currency;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\GetBnsActiveRestrictInfo
     */
    public function getGetBnsActiveRestrictInfo()
    {
        return $this->getBnsActiveRestrictInfo;
    }

    /**
     * @param \SB\Korona\Type\GetBnsActiveRestrictInfo $getBnsActiveRestrictInfo
     * @return InfoRequestData
     */
    public function withGetBnsActiveRestrictInfo($getBnsActiveRestrictInfo)
    {
        $new = clone $this;
        $new->getBnsActiveRestrictInfo = $getBnsActiveRestrictInfo;

        return $new;
    }


}

