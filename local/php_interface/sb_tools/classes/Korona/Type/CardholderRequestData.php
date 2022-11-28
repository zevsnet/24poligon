<?php

namespace SB\Korona\Type;

class CardholderRequestData
{

    /**
     * @var \SB\Korona\Type\TransactionData
     */
    private $transaction;

    /**
     * @var \SB\Korona\Type\CardholderInfoSeq
     */
    private $cardholderInfo;

    /**
     * @var \SB\Korona\Type\FormOptionsSeq
     */
    private $formOptions;

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
     * @return \SB\Korona\Type\TransactionData
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param \SB\Korona\Type\TransactionData $transaction
     * @return CardholderRequestData
     */
    public function withTransaction($transaction)
    {
        $new = clone $this;
        $new->transaction = $transaction;

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
     * @return CardholderRequestData
     */
    public function withCardholderInfo($cardholderInfo)
    {
        $new = clone $this;
        $new->cardholderInfo = $cardholderInfo;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\FormOptionsSeq
     */
    public function getFormOptions()
    {
        return $this->formOptions;
    }

    /**
     * @param \SB\Korona\Type\FormOptionsSeq $formOptions
     * @return CardholderRequestData
     */
    public function withFormOptions($formOptions)
    {
        $new = clone $this;
        $new->formOptions = $formOptions;

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
     * @return CardholderRequestData
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
     * @return CardholderRequestData
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
     * @return CardholderRequestData
     */
    public function withAccStatementParams($accStatementParams)
    {
        $new = clone $this;
        $new->accStatementParams = $accStatementParams;

        return $new;
    }


}

