<?php

namespace SB\Korona\Type;


class ChequeItem
{

    /**
     * @var \SB\Korona\Type\Product
     */
    private $product;

    /**
     * @var \SB\Korona\Type\Quantity
     */
    private $quantity;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @var \SB\Korona\Type\UnsignedLong
     */
    private $position;

    /**
     * @var \SB\Korona\Type\ChequeItemAttrSeq
     */
    private $attributes;

    /**
     * @return \SB\Korona\Type\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \SB\Korona\Type\Product $product
     * @return ChequeItem
     */
    public function withProduct($product)
    {
        $new = clone $this;
        $new->product = $product;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Quantity
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param \SB\Korona\Type\Quantity $quantity
     * @return ChequeItem
     */
    public function withQuantity($quantity)
    {
        $new = clone $this;
        $new->quantity = $quantity;

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
     * @return ChequeItem
     */
    public function withAmount(int $amount)
    {

        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\UnsignedLong
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param \SB\Korona\Type\UnsignedLong $position
     * @return ChequeItem
     */
    public function withPosition($position)
    {
        $new = clone $this;
        $new->position = $position;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ChequeItemAttrSeq
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param \SB\Korona\Type\ChequeItemAttrSeq $attributes
     * @return ChequeItem
     */
    public function withAttributes($attributes)
    {
        $new = clone $this;
        $new->attributes = $attributes;

        return $new;
    }


}

