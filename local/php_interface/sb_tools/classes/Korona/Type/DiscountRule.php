<?php

namespace SB\Korona\Type;

class DiscountRule
{

    /**
     * @var \SB\Korona\Type\CalculationParams
     */
    private $calcParams;

    /**
     * @var \SB\Korona\Type\LowBoundQnt
     */
    private $lowBoundQnt;

    /**
     * @var \SB\Korona\Type\HighBoundQnt
     */
    private $highBoundQnt;

    /**
     * @var \SB\Korona\Type\ProductGroup
     */
    private $prodGrp;

    /**
     * @var \SB\Korona\Type\ProdGrpUse
     */
    private $prodGrpUse;

    /**
     * @return \SB\Korona\Type\CalculationParams
     */
    public function getCalcParams()
    {
        return $this->calcParams;
    }

    /**
     * @param \SB\Korona\Type\CalculationParams $calcParams
     * @return DiscountRule
     */
    public function withCalcParams($calcParams)
    {
        $new = clone $this;
        $new->calcParams = $calcParams;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\LowBoundQnt
     */
    public function getLowBoundQnt()
    {
        return $this->lowBoundQnt;
    }

    /**
     * @param \SB\Korona\Type\LowBoundQnt $lowBoundQnt
     * @return DiscountRule
     */
    public function withLowBoundQnt($lowBoundQnt)
    {
        $new = clone $this;
        $new->lowBoundQnt = $lowBoundQnt;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\HighBoundQnt
     */
    public function getHighBoundQnt()
    {
        return $this->highBoundQnt;
    }

    /**
     * @param \SB\Korona\Type\HighBoundQnt $highBoundQnt
     * @return DiscountRule
     */
    public function withHighBoundQnt($highBoundQnt)
    {
        $new = clone $this;
        $new->highBoundQnt = $highBoundQnt;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ProductGroup
     */
    public function getProdGrp()
    {
        return $this->prodGrp;
    }

    /**
     * @param \SB\Korona\Type\ProductGroup $prodGrp
     * @return DiscountRule
     */
    public function withProdGrp($prodGrp)
    {
        $new = clone $this;
        $new->prodGrp = $prodGrp;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ProdGrpUse
     */
    public function getProdGrpUse()
    {
        return $this->prodGrpUse;
    }

    /**
     * @param \SB\Korona\Type\ProdGrpUse $prodGrpUse
     * @return DiscountRule
     */
    public function withProdGrpUse($prodGrpUse)
    {
        $new = clone $this;
        $new->prodGrpUse = $prodGrpUse;

        return $new;
    }


}

