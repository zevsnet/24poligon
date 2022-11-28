<?php

namespace SB\Korona\Type;

class DealType
{

    /**
     * @var \SB\Korona\Type\Id
     */
    private $id;

    /**
     * @var \SB\Korona\Type\Name
     */
    private $name;

    /**
     * @var \SB\Korona\Type\Desc
     */
    private $desc;

    /**
     * @var \SB\Korona\Type\Exclusive
     */
    private $exclusive;

    /**
     * @var \SB\Korona\Type\ConditionRuleSeq
     */
    private $condRules;

    /**
     * @var \SB\Korona\Type\DiscountRuleSeq
     */
    private $dscRules;

    /**
     * @return \SB\Korona\Type\Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \SB\Korona\Type\Id $id
     * @return DealType
     */
    public function withId($id)
    {
        $new = clone $this;
        $new->id = $id;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \SB\Korona\Type\Name $name
     * @return DealType
     */
    public function withName($name)
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Desc
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param \SB\Korona\Type\Desc $desc
     * @return DealType
     */
    public function withDesc($desc)
    {
        $new = clone $this;
        $new->desc = $desc;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Exclusive
     */
    public function getExclusive()
    {
        return $this->exclusive;
    }

    /**
     * @param \SB\Korona\Type\Exclusive $exclusive
     * @return DealType
     */
    public function withExclusive($exclusive)
    {
        $new = clone $this;
        $new->exclusive = $exclusive;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ConditionRuleSeq
     */
    public function getCondRules()
    {
        return $this->condRules;
    }

    /**
     * @param \SB\Korona\Type\ConditionRuleSeq $condRules
     * @return DealType
     */
    public function withCondRules($condRules)
    {
        $new = clone $this;
        $new->condRules = $condRules;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\DiscountRuleSeq
     */
    public function getDscRules()
    {
        return $this->dscRules;
    }

    /**
     * @param \SB\Korona\Type\DiscountRuleSeq $dscRules
     * @return DealType
     */
    public function withDscRules($dscRules)
    {
        $new = clone $this;
        $new->dscRules = $dscRules;

        return $new;
    }


}

