<?php

namespace SB\Korona\Type;

class CalculationParams
{

    /**
     * @var \SB\Korona\Type\Fix
     */
    private $fix;

    /**
     * @var \SB\Korona\Type\Percent
     */
    private $percent;

    /**
     * @var \SB\Korona\Type\MinResult
     */
    private $minResult;

    /**
     * @var \SB\Korona\Type\MaxResult
     */
    private $maxResult;

    /**
     * @return \SB\Korona\Type\Fix
     */
    public function getFix()
    {
        return $this->fix;
    }

    /**
     * @param \SB\Korona\Type\Fix $fix
     * @return CalculationParams
     */
    public function withFix($fix)
    {
        $new = clone $this;
        $new->fix = $fix;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Percent
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @param \SB\Korona\Type\Percent $percent
     * @return CalculationParams
     */
    public function withPercent($percent)
    {
        $new = clone $this;
        $new->percent = $percent;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\MinResult
     */
    public function getMinResult()
    {
        return $this->minResult;
    }

    /**
     * @param \SB\Korona\Type\MinResult $minResult
     * @return CalculationParams
     */
    public function withMinResult($minResult)
    {
        $new = clone $this;
        $new->minResult = $minResult;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\MaxResult
     */
    public function getMaxResult()
    {
        return $this->maxResult;
    }

    /**
     * @param \SB\Korona\Type\MaxResult $maxResult
     * @return CalculationParams
     */
    public function withMaxResult($maxResult)
    {
        $new = clone $this;
        $new->maxResult = $maxResult;

        return $new;
    }


}

