<?php

namespace SB\Korona\Type;

class Extension
{

    /**
     * @var \SB\Korona\Type\Type
     */
    private $type;

    /**
     * @var \SB\Korona\Type\Critical
     */
    private $critical;

    /**
     * @var \SB\Korona\Type\ExtParamSeq
     */
    private $params;

    /**
     * @return \SB\Korona\Type\Type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \SB\Korona\Type\Type $type
     * @return Extension
     */
    public function withType($type)
    {
        $new = clone $this;
        $new->type = $type;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\Critical
     */
    public function getCritical()
    {
        return $this->critical;
    }

    /**
     * @param \SB\Korona\Type\Critical $critical
     * @return Extension
     */
    public function withCritical($critical)
    {
        $new = clone $this;
        $new->critical = $critical;

        return $new;
    }

    /**
     * @return \SB\Korona\Type\ExtParamSeq
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param \SB\Korona\Type\ExtParamSeq $params
     * @return Extension
     */
    public function withParams($params)
    {
        $new = clone $this;
        $new->params = $params;

        return $new;
    }


}

