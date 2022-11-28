<?php

namespace SB\Korona\Type;

class ConditionRule
{

    /**
     * @var \SB\Korona\Type\MinQnt
     */
    private $minQnt;

    /**
     * @var \SB\Korona\Type\ProductGroup
     */
    private $prodGrp;

    /**
     * @var \SB\Korona\Type\ProdGrpUse
     */
    private $prodGrpUse;

    /**
     * @return \SB\Korona\Type\MinQnt
     */
    public function getMinQnt()
    {
        return $this->minQnt;
    }

    /**
     * @param \SB\Korona\Type\MinQnt $minQnt
     * @return ConditionRule
     */
    public function withMinQnt($minQnt)
    {
        $new = clone $this;
        $new->minQnt = $minQnt;

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
     * @return ConditionRule
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
     * @return ConditionRule
     */
    public function withProdGrpUse($prodGrpUse)
    {
        $new = clone $this;
        $new->prodGrpUse = $prodGrpUse;

        return $new;
    }


}

