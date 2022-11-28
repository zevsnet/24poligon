<?php

namespace SB\Korona\Type;

class CardholderInfoItem
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
     * @return \SB\Korona\Type\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \SB\Korona\Type\Name $name
     * @return CardholderInfoItem
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
     * @return CardholderInfoItem
     */
    public function withValue($value)
    {
        $new = clone $this;
        $new->value = $value;

        return $new;
    }


}

