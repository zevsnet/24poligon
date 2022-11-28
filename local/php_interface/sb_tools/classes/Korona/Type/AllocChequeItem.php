<?php

namespace SB\Korona\Type;

class AllocChequeItem
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
     * @var \SB\Korona\Type\PointsAmount
     */
    private $pointsAmount;

    /**
     * @return \SB\Korona\Type\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param \SB\Korona\Type\Product $product
     * @return AllocChequeItem
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
     * @return AllocChequeItem
     */
    public function withQuantity($quantity)
    {
        $new = clone $this;
        $new->quantity = $quantity;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\PointsAmount
     */
    public function getPointsAmount()
    {
        return $this->pointsAmount;
    }

    /**
     * @param \SB\Korona\Type\PointsAmount $pointsAmount
     * @return AllocChequeItem
     */
    public function withPointsAmount($pointsAmount)
    {
        $new = clone $this;
        $new->pointsAmount = $pointsAmount;

        return $new;
    }


}

