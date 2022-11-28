<?php

namespace SB\Korona\Type;

class IssuedDeal
{

    /**
     * @var \SB\Korona\Type\TypeId
     */
    private $typeId;

    /**
     * @var \SB\Korona\Type\StartDate
     */
    private $startDate;

    /**
     * @var \SB\Korona\Type\EndDate
     */
    private $endDate;

    /**
     * @return \SB\Korona\Type\TypeId
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * @param \SB\Korona\Type\TypeId $typeId
     * @return IssuedDeal
     */
    public function withTypeId($typeId)
    {
        $new = clone $this;
        $new->typeId = $typeId;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\StartDate
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \SB\Korona\Type\StartDate $startDate
     * @return IssuedDeal
     */
    public function withStartDate($startDate)
    {
        $new = clone $this;
        $new->startDate = $startDate;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\EndDate
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \SB\Korona\Type\EndDate $endDate
     * @return IssuedDeal
     */
    public function withEndDate($endDate)
    {
        $new = clone $this;
        $new->endDate = $endDate;

        return $new;
    }


}

