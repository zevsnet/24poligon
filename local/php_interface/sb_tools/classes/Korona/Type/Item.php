<?php

namespace SB\Korona\Type;

class Item
{

    /**
     * @var \SB\Korona\Type\PromoName
     */
    private $promoName;

    /**
     * @var \SB\Korona\Type\RestrictNote
     */
    private $restrictNote;

    /**
     * @var \SB\Korona\Type\Amount
     */
    private $amount;

    /**
     * @return \SB\Korona\Type\PromoName
     */
    public function getPromoName()
    {
        return $this->promoName;
    }

    /**
     * @param \SB\Korona\Type\PromoName $promoName
     * @return Item
     */
    public function withPromoName($promoName)
    {
        $new = clone $this;
        $new->promoName = $promoName;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\RestrictNote
     */
    public function getRestrictNote()
    {
        return $this->restrictNote;
    }

    /**
     * @param \SB\Korona\Type\RestrictNote $restrictNote
     * @return Item
     */
    public function withRestrictNote($restrictNote)
    {
        $new = clone $this;
        $new->restrictNote = $restrictNote;

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
     * @return Item
     */
    public function withAmount($amount)
    {
        $new = clone $this;
        $new->amount = $amount;

        return $new;
    }


}

