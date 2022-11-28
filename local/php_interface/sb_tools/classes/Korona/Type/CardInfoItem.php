<?php

namespace SB\Korona\Type;

class CardInfoItem
{

    /**
     * @var \SB\Korona\Type\Name
     */
    private $name;

    /**
     * @var \SB\Korona\Type\Value
     */
    private $value;

    /**
     * @var \SB\Korona\Type\Type
     */
    private $type;

    /**
     * @var \SB\Korona\Type\Relation
     */
    private $relation;

    /**
     * @return \SB\Korona\Type\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \SB\Korona\Type\Name $name
     * @return CardInfoItem
     */
    public function withName($name)
    {
        $new = clone $this;
        $new->name = $name;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Value
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \SB\Korona\Type\Value $value
     * @return CardInfoItem
     */
    public function withValue($value)
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \SB\Korona\Type\Type $type
     * @return CardInfoItem
     */
    public function withType($type)
    {
        $new = clone $this;
        $new->type = $type;

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
     * @return CardInfoItem
     */
    public function withRelation($relation)
    {
        $new = clone $this;
        $new->relation = $relation;

        return $new;
    }


}

