<?php

namespace SB\Korona\Type;

class OperSummaryItem
{

    /**
     * @var \SB\Korona\Type\Name
     */
    private $name;

    /**
     * @var \SB\Korona\Type\Relation
     */
    private $relation;

    /**
     * @var \SB\Korona\Type\Before
     */
    private $before;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @var \SB\Korona\Type\AmountInc
     */
    private $amountInc;

    /**
     * @var \SB\Korona\Type\AmountDec
     */
    private $amountDec;

    /**
     * @var \SB\Korona\Type\After
     */
    private $after;

    /**
     * @return \SB\Korona\Type\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \SB\Korona\Type\Name $name
     * @return OperSummaryItem
     */
    public function withName($name)
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Relation
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * @param \SB\Korona\Type\Relation $relation
     * @return OperSummaryItem
     */
    public function withRelation($relation)
    {
        $new = clone $this;
        $new->relation = $relation;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Before
     */
    public function getBefore()
    {
        return $this->before;
    }

    /**
     * @param \SB\Korona\Type\Before $before
     * @return OperSummaryItem
     */
    public function withBefore($before)
    {
        $new = clone $this;
        $new->before = $before;

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
     * @return OperSummaryItem
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AmountInc
     */
    public function getAmountInc()
    {
        return $this->amountInc;
    }

    /**
     * @param \SB\Korona\Type\AmountInc $amountInc
     * @return OperSummaryItem
     */
    public function withAmountInc($amountInc)
    {
        $new = clone $this;
        $new->amountInc = $amountInc;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\AmountDec
     */
    public function getAmountDec()
    {
        return $this->amountDec;
    }

    /**
     * @param \SB\Korona\Type\AmountDec $amountDec
     * @return OperSummaryItem
     */
    public function withAmountDec($amountDec)
    {
        $new = clone $this;
        $new->amountDec = $amountDec;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\After
     */
    public function getAfter()
    {
        return $this->after;
    }

    /**
     * @param \SB\Korona\Type\After $after
     * @return OperSummaryItem
     */
    public function withAfter($after)
    {
        $new = clone $this;
        $new->after = $after;

        return $new;
    }


}

